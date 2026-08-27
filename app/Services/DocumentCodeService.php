<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorSVG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentCodeService
{
    public function generate(string $documentNo): array
    {
        $fname = basename($documentNo).".svg";
        $imagenewname = uniqid().$fname;
        $filepath = 'assets/img/documents/'.$imagenewname; 

        $filename = public_path($filepath);
        $dirname = dirname($filename);
   
        $qrCode = QrCode::format('svg')
        ->size(300)
        ->margin(0)
        ->generate($documentNo);

        if (!file_exists($dirname)) {
            mkdir($dirname, 0755, true);
        }
        file_put_contents($filename, $qrCode);
        
        $codeArrs['qr_code_path'] = $filepath;
        /*
         * Barcode
        */
        $imagenewname = uniqid().$fname;
        $filepath = 'assets/img/documents/'.$imagenewname; 

        $filename = public_path($filepath);
        $dirname = dirname($filename);
   

        $barcodeGenerator = new BarcodeGeneratorSVG();

        $barCode = $barcodeGenerator->getBarcode(
            $documentNo,
            BarcodeGeneratorSVG::TYPE_CODE_128
        );

        if (!file_exists($dirname)) {
            mkdir($dirname, 0755, true);
        }
        file_put_contents($filename, $barCode);
        
        $codeArrs['barcode_path'] = $filepath;


        return $codeArrs;
        
    }

    
}