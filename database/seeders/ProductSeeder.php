<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table("products")->insert([
                "name" => Str::random(10),
                "description" => Str::random(10) . "@gmail.com",
                "quantity" => random_int(0, 100),
                "price" => random_int(0, 200),
                "fake_price" => random_int(0, 200),
                "slug" => Str::random(10),
                "created_at" => Carbon::today()->subDays(rand(0, 365)),
                "updated_at" => Carbon::today()->subDays(rand(0, 365)),
            ]);
        }
    }
}
