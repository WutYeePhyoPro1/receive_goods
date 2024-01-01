<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class departmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['Admin','Construction','Designer','Finance & Accounting','HR'
                ,'Marketing','Merchandise','Online Sale','Operation','Project Sale','Sourcing','System Development','Lanthit','Zin Htet'];

        foreach($data as $item){
            DB::table('departments')->insert([
                'name'  => $item,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
