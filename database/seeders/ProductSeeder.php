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

        for ($i = 0; $i < 50; $i++) { // creating 50 products
            $product = Product::create([
                'title' => $faker->word, // Changed from 'name' to 'title'
                'price' => $faker->randomFloat(2, 10, 500),
                'description' => $faker->sentence,
                'image' => $faker->imageUrl(640, 480, 'products'),
                'rating' => [
                    'rate' => $faker->randomFloat(2, 1, 5),
                    'count' => $faker->numberBetween(0, 1000),
                ],
            ]);

            // assigning random catalogs to the product
            $randomCatalogs = $faker->randomElements($catalogIds, rand(1, 3));
            $product->catalogs()->attach($randomCatalogs);
        }
    }
}
