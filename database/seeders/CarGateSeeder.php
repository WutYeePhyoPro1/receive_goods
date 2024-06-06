<?php

namespace Database\Seeders;

use App\Models\CarGate;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CarGateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = ['MM-505','MM-505','MM-510','MM-510','MM-511','MM-511','MM-511','MM-511','MM-511','MM-511','MM-101','MM-101'];
        $name   = ['SH1-01','SH1-02','SH2-01','SH2-02','SH3-01','SH3-02','SH3-03','SH3-04','SH3-05','SH3-06','Gate_1','Gate_2'];

        for($i = 0 ; $i < count($name); $i++)
        {
            CarGate::create([
                'branch'    => $branch[$i],
                'name'      => $name[$i]
            ]);
        }
    }
}
