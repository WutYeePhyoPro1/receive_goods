<?php

namespace Database\Seeders;

use App\Models\User;
use Pro1\Changelog\Models\ChangeType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ChangeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ChangeType::create([
            "name" => "Bug Fixes",
            "slug" => Str::slug("Bug Fixes"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        ChangeType::create([
            "name" => "Improvements",
            "slug" => Str::slug("Improvements"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        ChangeType::create([
            "name" => "New Feature",
            "slug" => Str::slug("New Feature"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);
    }
}
