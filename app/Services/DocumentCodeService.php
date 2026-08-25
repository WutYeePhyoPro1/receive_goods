<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorSVG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentCodeService
{
    public function generate(string $documentNo): array
    {
        $safeName = str_replace(['/', '\\'], '-', $documentNo);

        /*
         * Barcode
         */
        $barcodeGenerator = new BarcodeGeneratorSVG();

        $barcodeSvg = $barcodeGenerator->getBarcode(
            $documentNo,
            BarcodeGeneratorSVG::TYPE_CODE_128
        );

        $barcodePath = "barcodes/{$safeName}.svg";

        Storage::disk('public')->put(
            $barcodePath,
            $barcodeSvg
        );

        /*
         * QR Code
         */
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($documentNo);

        $qrPath = "qrcodes/{$safeName}.svg";

        Storage::disk('public')->put(
            $qrPath,
            $qrSvg
        );

        return [
            'barcode_path' => $barcodePath,
            'qr_code_path' => $qrPath,
        ];
    }
}