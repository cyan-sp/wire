<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Yum Brands
        $yumBrands = Company::create([
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
        ]);

        // Associate KFC (1) and Pizza Hut (2) with Yum Brands
        Brand::whereIn('id', [1, 2])->update(['company_id' => $yumBrands->id]);

        // Jollibee Foods
        $jollibee = Company::create([
            'company_name' => 'Jollibee Foods',
            'legal_name' => 'Jollibee Foods Corporation',
            'tax_id' => 'TAX-1234567',
            'phone' => '632-1234-5678',
            'address' => 'Jollibee Plaza, 10 F. Ortigas Jr. Road, Pasig City',
            'email' => 'info@jollibee.com',
            'website' => 'https://www.jollibee.com.ph',
            'city' => 'Pasig City',
            'state' => 'Metro Manila',
            'country' => 'Philippines',
            'status' => true,
            'logo' => 'https://companieslogo.com/img/orig/JBFCF-f8098eac.png?t=1720244492',
        ]);

        // Associate Taco Bell (3) and Greenwich (4) with Jollibee Foods
        Brand::whereIn('id', [3, 4])->update(['company_id' => $jollibee->id]);
    }
}
