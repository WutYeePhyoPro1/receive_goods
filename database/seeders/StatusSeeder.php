<?php

namespace Database\Seeders;

use App\Models\User;
use Pro1\Changelog\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            "id" => 7,
            "name" => "Public",
            "slug" => Str::slug("Public"),
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        Status::create([
            "id" => 19,
            "name" => "Draft",
            "slug" => Str::slug("Draft"),
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);

        Status::create([
            "id" => 20,
            "name" => "Scheduled",
            "slug" => Str::slug("Scheduled"),
            // "user_id" => User::where('email',"pro1@mail.com")->first()->id
            "user_id" => User::first()->id
        ]);
    }
}
