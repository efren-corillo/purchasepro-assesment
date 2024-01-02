<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // truncate the table
        Catalog::truncate();

        // enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $catalogs = [
            'appliances' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2016/01/24-2-70x70.png',
            'lumber' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2016/01/19-70x70.png',
            'paint' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2016/01/7-70x70.png',
            'plumbing' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2016/01/21-1-70x70.png',
            'seasonal' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2016/01/rake-2-70x70.png',
            'signs' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2018/05/2-70x70.png',
            'tools and hardware' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2018/05/22-70x70.png',
            'lighting' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2018/05/20-2-70x70.png',
            'kitchen renovations' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2018/05/12-70x70.png',
            'electrical supplies' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2016/01/23-70x70.png',
            'bathroom renovations' => 'https://97ra37.p3cdn1.secureserver.net/wp-content/uploads/2018/05/13-70x70.png',
        ];

        foreach ($catalogs as $name => $icon) {
            Catalog::create(['name' => $name, 'icon' => $icon]);
        }
    }
}
