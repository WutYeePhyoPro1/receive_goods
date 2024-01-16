<?php

namespace Database\Seeders;

use App\Models\Truck;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $truck = [
            "Twelve - wheeler Truck",
            "Trailer",
            "Taxi",
            "Motorcycle",
            "21Ft - Flat",
            "21Ft - Box",
            "14 Ft - Box",
            "10 Ft - Box",
            "14Ft - Flat",
            "10Ft - Flat",
            "16Ft - Flat",
            "16Ft - Box",
            "Hijet",
            "Light Truck"
        ];

        foreach($truck as $item)
        {
            Truck::create([
                'truck_name' => $item
            ]);
        }
    }
}
