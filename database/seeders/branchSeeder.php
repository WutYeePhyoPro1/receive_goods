<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class branchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::factory()->create([ 'branch_name' => 'Head Office','branch_code' => 'MM-001']);
        Branch::factory()->create([ 'branch_name' => 'Lanthit','branch_code' => 'MM-101']);
        Branch::factory()->create([ 'branch_name' => 'Theik Pan','branch_code' => 'MM-102']);
        Branch::factory()->create([ 'branch_name' => 'Satsan','branch_code' => 'MM-103']);
        Branch::factory()->create([ 'branch_name' => 'East Dagon','branch_code' => 'MM-104']);
        Branch::factory()->create([ 'branch_name' => 'Mawlamyine','branch_code' => 'MM-105']);
        Branch::factory()->create([ 'branch_name' =>'Tampawady','branch_code' => 'MM-106']);
        Branch::factory()->create([ 'branch_name' =>'Hlaingtharyar','branch_code' => 'MM-107']);
        Branch::factory()->create([ 'branch_name' =>'Ayetharyar','branch_code' => 'MM-109']);
        Branch::factory()->create([ 'branch_name' =>'Bago','branch_code' => 'MM-110']);
        Branch::factory()->create([ 'branch_name' =>'PRO1 PLUS (Terminal M)','branch_code' => 'MM-112']);
        Branch::factory()->create([ 'branch_name' =>'South Dagon','branch_code' => 'MM-113']);
        Branch::factory()->create([ 'branch_name' =>'Project Sales','branch_code' => 'MM-201']);
        Branch::factory()->create([ 'branch_name' =>'Online Sales','branch_code' => 'MM-202']);
        Branch::factory()->create([ 'branch_name' =>'Whole Sales','branch_code' => 'MM-203']);
        Branch::factory()->create([ 'branch_name' =>'WH-Myo Houng','branch_code' => 'MM-504']);
        Branch::factory()->create([ 'branch_name' =>'WH-Mingalardon','branch_code' => 'MM-505']);
        Branch::factory()->create([ 'branch_name' =>'DC-Myawaddy','branch_code' => 'MM-509']);
        Branch::factory()->create([ 'branch_name' =>'DC-Mingalardon2','branch_code' => 'MM-510']);
        Branch::factory()->create([ 'branch_name' =>'DC-Mingalardon3','branch_code' => 'MM-511']);
        Branch::factory()->create(['branch_name'=>'Da Nyin Gone','branch_code'=> 'MM-114']);
        Branch::factory()->create(['branch_name'=>'Clearance Sale','branch_code'=> 'MM-205']);
    }
}
