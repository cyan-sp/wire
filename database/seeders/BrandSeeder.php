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
//            'company_id' => 1, // Adjust to a valid company ID
        ]);
        Brand::create([
            'name' => 'Pizza hut',
//            'company_id' => 2, // Adjust to a valid company ID
        ]);
        Brand::create([
            'name' => ' Olive Garden',
//            'company_id' => 3, // Adjust to a valid company ID
        ]);
    }
}
