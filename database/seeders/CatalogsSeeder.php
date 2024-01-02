<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Catalog;


class CatalogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $catalogs = [
            'appliances',
            'lumber',
            'paint',
            'plumbing',
            'seasonal',
            'signs',
            'tools and hardware',
            'lighting',
            'kitchen renovations',
            'electrical supplies',
            'bathroom renovations',
        ];

        foreach ($catalogs as $catalog) {
            Catalog::create(['name' => $catalog]);
        }
    }
}
