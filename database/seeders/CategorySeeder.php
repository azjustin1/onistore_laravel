<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 3; $i++) {
            DB::table("categories")->insert([
                "name" => Str::random(10),
                "description" => Str::random(10),
                "slug" => Str::random(10),
            ]);
        }
    }
}