<?php

namespace Database\Seeders;

use App\Models\User;
use Pro1\Changelog\Models\ReleaseType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ReleaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReleaseType::create([
            "name" => "Major",
            "slug" => Str::slug("Major"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        ReleaseType::create([
            "name" => "Minor",
            "slug" => Str::slug("Minor"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        ReleaseType::create([
            "name" => "Patch (Fix)",
            "slug" => Str::slug("Patch (Fix)"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        ReleaseType::create([
            "name" => "Urgent Fix",
            "slug" => Str::slug("Urgent Fix"),
            "status_id" => 1,
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);
    }
}
