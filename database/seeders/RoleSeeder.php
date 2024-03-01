<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = ['admin','user','supervisor','manager'];

        foreach($role as $item)
        {
            $role = Role::create(['name'=>$item]);
            if($item == 'user')
            {
                $permission = Permission::whereIn('permission_id',[1,8])->pluck('id','id')->all();
            }elseif($item == 'supervisor')
            {
                $permission = Permission::whereIn('permission_id',[1,2,6,7,8])->pluck('id','id')->all();
            }elseif($item == 'manager')
            {
                $permission = Permission::whereIn('permission_id',[2,6,7])->pluck('id','id')->all();
            }elseif($item == 'admin')
            {
                $permission = Permission::whereIn('permission_id',[2,3,4,5,6,7])->pluck('id','permission_id')->all();
            }

            $role->syncPermissions($permission);
        }
    }
}
