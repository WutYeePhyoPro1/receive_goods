<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $source = ['Local Supplier','Shipment jetty-1','Shipment jetty-2','Shipment jetty-3','Shipment container-1','Myawaddy','Muse'];

        foreach($source as $item)
        {
            Source::create([
                'name'  => $item
            ]);
        }
    }
}
