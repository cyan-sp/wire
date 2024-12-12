<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'KFC',
            'logo' => 'https://upload.wikimedia.org/wikipedia/sco/thumb/b/bf/KFC_logo.svg/2048px-KFC_logo.svg.png'
//            'company_id' => 1, // Adjust to a valid company ID
        ]);
        Brand::create([
            'name' => 'Pizza hut',
            'logo' => 'https://upload.wikimedia.org/wikipedia/sco/thumb/d/d2/Pizza_Hut_logo.svg/2177px-Pizza_Hut_logo.svg.png'
//            'company_id' => 2, // Adjust to a valid company ID
        ]);
        Brand::create([
            'name' => 'Taco Bell',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/b/b3/Taco_Bell_2016.svg/1200px-Taco_Bell_2016.svg.png'
//            'company_id' => 3, // Adjust to a valid company ID
        ]);
        Brand::create([
            'name' => 'Greenwich',
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Greenwich_Pizza_logo.svg/760px-Greenwich_Pizza_logo.svg.png'
            //            'company_id' => 3, // Adjust to a valid company ID
        ]);



    }
}
