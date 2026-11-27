<?php

namespace App\Exports\R008;

use App\Exports\Concerns\BaseExport;
use App\Services\R008\R008DocumentQuery;
use Carbon\Carbon;

class R008DocumentExport extends BaseExport
{
    public function __construct(protected array $filters = [])
    {
    }

    public function query()
    {
        return app(R008DocumentQuery::class)
            ->build($this->filters)
            ->orderByDesc('document_date');
    }

    public function headings(): array
    {
        return [
            'Doc. No',
            'Doc. Date',
            'Branch',
            'Vendor Code',
            'Doc RG No.',
            'Invoice No',
        ];
    }

    public function map($row): array
    {
        return [
            $row->r008_files->first()?->file ?? '',
            Carbon::parse($row->document_date),
            $row->branch?->branch_name ?? '',
            $row->vendor_code,
            $row->rg_no,
            $row->invoice_no ?? '',
        ];
    }

    public function title(): string
    {
        return 'R008 Documents';
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'yyyy-mm-dd',
        ];
    }
}
