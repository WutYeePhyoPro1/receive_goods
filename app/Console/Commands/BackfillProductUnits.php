<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BackfillProductUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:backfill-units
        {--commit : Update products.unit. Without this option the command only reports what would change.}
        {--chunk=1000 : Number of products to process per batch.}
        {--limit= : Maximum number of products to inspect.}
        {--document= : Backfill only one document number.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill missing product units from source PO, transfer, and outbound data';

    private array $poUnitCache = [];
    private array $transferUnitCache = [];
    private array $outboundUnitCache = [];

    public function handle(): int
    {
        $commit = (bool) $this->option('commit');
        $chunkSize = max(1, (int) $this->option('chunk'));
        $limit = $this->option('limit') ? max(1, (int) $this->option('limit')) : null;
        $documentNo = $this->option('document') ?: null;

        if ($commit && ! DB::getSchemaBuilder()->hasTable('product_unit_backfills')) {
            $this->error('Backup table product_unit_backfills does not exist. Run php artisan migrate first.');

            return self::FAILURE;
        }

        $totalMissing = $this->baseQuery($documentNo)->count();
        $this->info('Products with missing unit: '.$totalMissing);
        $this->line($commit ? 'Mode: COMMIT' : 'Mode: DRY RUN');

        $stats = [
            'checked' => 0,
            'matched' => 0,
            'updated' => 0,
            'not_found' => 0,
            'po' => 0,
            'transfer' => 0,
            'outbound' => 0,
        ];
        $samples = [];
        $remaining = $limit;

        $query = $this->baseQuery($documentNo)->orderBy('p.id');
        $query->chunkById($chunkSize, function (Collection $products) use (&$stats, &$samples, &$remaining, $commit, $limit) {
            if ($limit !== null && $remaining <= 0) {
                return false;
            }

            if ($limit !== null && $products->count() > $remaining) {
                $products = $products->take($remaining)->values();
            }

            $stats['checked'] += $products->count();
            $matches = $this->resolveUnits($products);

            foreach ($products as $product) {
                $match = $matches[$product->product_id] ?? null;

                if (! $match || empty($match['unit'])) {
                    $stats['not_found']++;
                    $this->addSample($samples, $product, null);
                    continue;
                }

                $stats['matched']++;
                $stats[$match['source']]++;
                $this->addSample($samples, $product, $match);

                if ($commit) {
                    DB::table('product_unit_backfills')->updateOrInsert(
                        ['product_id' => $product->product_id],
                        [
                            'document_id' => $product->document_id,
                            'document_no' => $product->document_no,
                            'outbound' => $product->outbound,
                            'bar_code' => $product->bar_code,
                            'old_unit' => $product->unit,
                            'new_unit' => $match['unit'],
                            'source' => $match['source'],
                            'backfilled_at' => now(),
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );

                    DB::table('products')
                        ->where('id', $product->product_id)
                        ->where(function ($query) {
                            $query->whereNull('unit')->orWhere('unit', '');
                        })
                        ->update([
                            'unit' => $match['unit'],
                            'updated_at' => now(),
                        ]);

                    $stats['updated']++;
                }
            }

            if ($limit !== null) {
                $remaining -= $products->count();

                if ($remaining <= 0) {
                    return false;
                }
            }

            return true;
        }, 'p.id', 'product_id');

        $this->newLine();
        $this->table(
            ['Checked', 'Matched', 'Updated', 'Not Found', 'PO', 'Transfer', 'Outbound'],
            [[
                $stats['checked'],
                $stats['matched'],
                $stats['updated'],
                $stats['not_found'],
                $stats['po'],
                $stats['transfer'],
                $stats['outbound'],
            ]]
        );

        if ($samples) {
            $this->newLine();
            $this->table(
                ['Product ID', 'Document', 'Outbound', 'Barcode', 'Old Unit', 'New Unit', 'Source'],
                $samples
            );
        }

        if (! $commit) {
            $this->warn('Dry run only. Re-run with --commit to update products.unit and write backups.');
        }

        return self::SUCCESS;
    }

    private function baseQuery(?string $documentNo)
    {
        return DB::table('products as p')
            ->join('documents as d', 'p.document_id', '=', 'd.id')
            ->select([
                'p.id as product_id',
                'p.document_id',
                'p.bar_code',
                'p.unit',
                'd.document_no',
                'd.outbound',
            ])
            ->where(function ($query) {
                $query->whereNull('p.unit')->orWhere('p.unit', '');
            })
            ->when($documentNo, function ($query) use ($documentNo) {
                $query->where('d.document_no', $documentNo);
            });
    }

    private function resolveUnits(Collection $products): array
    {
        $matches = [];

        foreach ($products->groupBy('document_no') as $documentNo => $documentProducts) {
            $barcodes = $documentProducts->pluck('bar_code')->filter()->unique()->values()->all();

            if (str_starts_with(strtoupper($documentNo), 'PO')) {
                $units = $this->getPoUnits($documentNo, $barcodes);
                $source = 'po';
            } elseif ($documentProducts->contains(fn ($product) => ! empty($product->outbound))) {
                $units = $this->getOutboundUnits($barcodes);
                $source = 'outbound';
            } else {
                $units = $this->getTransferUnits($documentNo, $barcodes);
                $source = 'transfer';
            }

            foreach ($documentProducts as $product) {
                if (! empty($units[$product->bar_code])) {
                    $matches[$product->product_id] = [
                        'unit' => $units[$product->bar_code],
                        'source' => $source,
                    ];
                }
            }
        }

        return $matches;
    }

    private function getPoUnits(string $documentNo, array $barcodes): array
    {
        if (! isset($this->poUnitCache[$documentNo])) {
            $rows = DB::connection('master_product')->select(
                "
                select bb.productcode, max(bb.unitcount) as unit
                from purchaseorder.po_purchaseorderhd aa
                inner join purchaseorder.po_purchaseorderdt bb on aa.purchaseid = bb.purchaseid
                where aa.statusflag <> 'C'
                and aa.statusflag in ('P','Y','F')
                and aa.purchaseno = ?
                group by bb.productcode
                ",
                [$documentNo]
            );

            $this->poUnitCache[$documentNo] = $this->rowsToUnitMap($rows, 'productcode');
        }

        return $this->onlyBarcodes($this->poUnitCache[$documentNo], $barcodes);
    }

    private function getTransferUnits(string $documentNo, array $barcodes): array
    {
        if (! isset($this->transferUnitCache[$documentNo])) {
            $rows = DB::connection('master_product')->select(
                "
                select todt.productcode as product_code, max(todt.unitcount) as unit
                from inventory.trs_transferouthd tohd
                left join inventory.trs_transferoutdt todt on tohd.transferid = todt.transferid
                where tohd.transferdocno = ?
                and tohd.statusid <> 'C'
                group by todt.productcode
                ",
                [$documentNo]
            );

            $this->transferUnitCache[$documentNo] = $this->rowsToUnitMap($rows, 'product_code');
        }

        return $this->onlyBarcodes($this->transferUnitCache[$documentNo], $barcodes);
    }

    private function getOutboundUnits(array $barcodes): array
    {
        $missing = array_values(array_filter($barcodes, fn ($barcode) => ! array_key_exists($barcode, $this->outboundUnitCache)));

        if ($missing) {
            foreach (array_chunk($missing, 500) as $barcodeChunk) {
                $placeholders = implode(',', array_fill(0, count($barcodeChunk), '?'));
                $rows = DB::connection('dc_connection')->select(
                    "
                    select product_code, max(product_unit_code) as unit
                    from master_data.master_product
                    where product_code in ($placeholders)
                    group by product_code
                    ",
                    $barcodeChunk
                );

                foreach ($barcodeChunk as $barcode) {
                    $this->outboundUnitCache[$barcode] = null;
                }

                foreach ($rows as $row) {
                    $this->outboundUnitCache[$row->product_code] = $row->unit;
                }
            }
        }

        return $this->onlyBarcodes($this->outboundUnitCache, $barcodes);
    }

    private function rowsToUnitMap(array $rows, string $barcodeColumn): array
    {
        $units = [];

        foreach ($rows as $row) {
            if (! empty($row->{$barcodeColumn}) && ! empty($row->unit)) {
                $units[$row->{$barcodeColumn}] = $row->unit;
            }
        }

        return $units;
    }

    private function onlyBarcodes(array $units, array $barcodes): array
    {
        return array_intersect_key($units, array_flip($barcodes));
    }

    private function addSample(array &$samples, object $product, ?array $match): void
    {
        if (count($samples) >= 20) {
            return;
        }

        $samples[] = [
            $product->product_id,
            $product->document_no,
            $product->outbound,
            $product->bar_code,
            $product->unit ?? 'NULL',
            $match['unit'] ?? 'NOT FOUND',
            $match['source'] ?? '-',
        ];
    }
}
