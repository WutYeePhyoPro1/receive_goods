<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user                   = new User();
        $user->name             = 'admin';
        $user->employee_code    = '000-000000';
        $user->password         = Hash::make('admin123');
        $user->password_str    = 'admin123';
        $user->department_id    = 1;
        $user->branch_id        = 1;
        $user->active           = true;
        $user->save();
    }
}
