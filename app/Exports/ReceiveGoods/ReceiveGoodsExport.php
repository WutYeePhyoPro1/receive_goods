<?php

namespace App\Exports\ReceiveGoods;

use App\Exports\Concerns\BaseExport;
use App\Services\ReceiveGoods\ReceiveGoodsQuery;

class ReceiveGoodsExport extends BaseExport
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return app(ReceiveGoodsQuery::class)
            ->build($this->filters)
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    public function headings(): array
    {
        return [
            'RG Doc No',
            'PO Doc No',
            'Document Date',
            'Back Date',
            'Branch',
            'Vendor Name',
            'R008',
            'R008 Doc No',
            'Remark',
        ];
    }

    public function map($row): array
    {
        return [
            $row->receive_good_files->first()?->file ?? '',
            $row->po_no,
            $row->created_at,
            $row->date,
            $row->branch?->branch_name ?? '',
            $row->vendor?->vendor_name ?? '',
            $row->r008 ? 'Yes' : 'No',
            $row->receive_good_files->firstWhere('name', 'R008')?->file ?? '-',
            $row->remark,
        ];
    }

    public function title(): string
    {
        return 'Receive Goods';
    }

    public function columnFormats(): array
    {
        return [
            'C' => 'yyyy-mm-dd',
            'D' => 'yyyy-mm-dd',
        ];
    }
}
