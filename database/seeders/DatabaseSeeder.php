<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // PermissionSeeder::class,
            // RoleSeeder::class,
            UserSeeder::class,
            departmentSeeder::class,
            branchSeeder::class,
            TruckSeeder::class,
            SourceSeeder::class,
            CarGateSeeder::class,
            PrintReasonSeeder::class,

            // Add more seeder classes as needed
        ]);
    }
}
