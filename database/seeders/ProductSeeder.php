<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use App\Models\Catalog;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(Faker $faker): void
    {
        $catalogIds = Catalog::pluck('id')->toArray();

        Product::factory(50)->create()->each(function ($product) use ($catalogIds) {
            $randomCatalogs = array_rand(array_flip($catalogIds), rand(1, 3));
            $product->catalogs()->attach($randomCatalogs);
        });
    }
}
