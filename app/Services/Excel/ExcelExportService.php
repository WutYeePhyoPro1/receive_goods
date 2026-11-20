<?php

namespace App\Services\Excel;

use Maatwebsite\Excel\Facades\Excel;

class ExcelExportService
{
    public function download(
        object $export,
        string $fileName
    ) {
        $fileName = $this->sanitizeFileName($fileName);

        return Excel::download(
            $export,
            $fileName . '.xlsx'
        );
    }

    public function store(
        object $export,
        string $fileName,
        string $disk = 'local'
    ) {
        $fileName = $this->sanitizeFileName($fileName);

        return Excel::store(
            $export,
            $fileName . '.xlsx',
            $disk
        );
    }

    private function sanitizeFileName(string $fileName): string
    {
        return preg_replace(
            '/[^A-Za-z0-9_-]/',
            '_',
            $fileName
        );
    }
}
