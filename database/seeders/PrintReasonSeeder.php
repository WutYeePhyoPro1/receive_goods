<?php

namespace Database\Seeders;

use App\Models\PrintReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrintReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reason = [
            'Supplier Barcode not clear','Supplier Barcode wrong','Supplier not support for barcode','Supplier Barcode paper low quality'
        ];

        PrintReason::truncate();
        foreach($reason as $item)
        {
            PrintReason::create([
                'reason'    => $item
            ]);
        }
    }
}
