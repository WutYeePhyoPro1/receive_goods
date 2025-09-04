<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Pro1\Changelog\Models\PriorityLevel;
use Illuminate\Database\Seeder;

class PriorityLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PriorityLevel::create([
            "name" => "Low",
            "slug" => Str::slug("Low"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id,
            "user_id" => User::first()->id
        ]);

        PriorityLevel::create([
            "name" => "Medium",
            "slug" => Str::slug("Medium"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        PriorityLevel::create([
            "name" => "High",
            "slug" => Str::slug("High"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        PriorityLevel::create([
            "name" => "Critical",
            "slug" => Str::slug("Critical"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);
    }
}
