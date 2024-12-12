<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'company_name' => 'Yum brands',
            'legal_name' => 'Yum brands legal name',
            'tax_id' => 'TAX-1234567',
            'phone' => '555-1234',
            'address' => '1234 Elm Street, Cityville',
            'email' => 'info@yumbrands.com',
            'website' => 'https://www.yumbrands.com',
            'city' => 'Cityville',
            'state' => 'Stateburg',
            'country' => 'USA',
            'status' => true,
            'logo' => 'https://s3-symbol-logo.tradingview.com/yum-brands--600.png',
            // Leave team_id out for now
        ]);

        Company::create([
            'company_name' => 'Darden Restaurants',
            'legal_name' => 'Darden Restaurants, Inc.',
            'tax_id' => 'TAX-9876543',
            'phone' => '407-245-4000',
            'address' => '1000 Darden Center Drive, Orlando, FL 32837',
            'email' => 'info@darden.com',
            'website' => 'https://www.darden.com',
            'city' => 'Orlando',
            'state' => 'Florida',
            'country' => 'USA',
            'status' => true,
            'logo' => 'https://s3-symbol-logo.tradingview.com/darden-restaurants--600.png',
            // Leave team_id out for now
        ]);

        //
    }
}
