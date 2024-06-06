<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        $data = [
            ['permission_id' => 1,'name'=>'barcode-scan'],
            ['permission_id' => 2,'name'=>'view-detail-report'],
            ['permission_id' => 3,'name'=>'user-management'],
            ['permission_id' => 4,'name'=>'role-management'],
            ['permission_id' => 5,'name'=>'permission-management'],
            ['permission_id' => 6,'name'=>'adjust-excess'],
            ['permission_id' => 7,'name'=>'adjust-truck-goods'],
            ['permission_id' => 8,'name'=>'add-new-truck'],
        ];

        foreach($data as $item)
        {
            Permission::create($item);
        }
    }
}
