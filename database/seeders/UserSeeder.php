<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user                   = new User();
        $user->name             = 'admin';
        $user->employee_code    = 'superadmin@mail.com';
        $user->password         = Hash::make('admin123');
        $user->password_str     = 'admin123';
        $user->role             = 1;
        $user->department_id    = 1;
        $user->branch_id        = 1;
        $user->active           = true;
        $user->save();

        $role = Role::create(['name'=>'admin']);
        $permission = Permission::whereIn('permission_id',[2,3,4,5,6,7])->pluck('id','permission_id')->all();
        $role->syncPermissions($permission);
        $user->assignRole([$role->name]);
    }
}
