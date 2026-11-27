<?php

namespace App\Exports\Concerns;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithEvents,
    WithColumnFormatting
{
    abstract public function query();

    abstract public function headings(): array;

    abstract public function map($row): array;

    public function title(): string
    {
        return 'Sheet1';
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                    'size' => 11,
                ],

                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => '2563EB',
                    ],
                ],

                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastColumn = $sheet->getHighestColumn();

                // Freeze header row
                $sheet->freezePane('A2');

                // Enable Excel filters
                $sheet->setAutoFilter(
                    "A1:{$lastColumn}1"
                );

                // Header height
                $sheet->getRowDimension(1)
                    ->setRowHeight(25);

                // Vertical alignment
                $sheet->getStyle(
                    "A1:{$lastColumn}{$sheet->getHighestRow()}"
                )->getAlignment()
                    ->setVertical('center');
            },
        ];
    }
}
