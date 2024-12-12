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
            'logo' => 'https://companieslogo.com/img/orig/JBFCF-f8098eac.png?t=1720244492', // Replace this with the actual logo URL
        ]);

        //
    }
}
