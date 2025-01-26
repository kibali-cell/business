<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Company::create([
            'name' => 'Example Corp',
            'email' => 'info@example.com',
            'phone' => '987-654-3210',
            'address' => '123 Main St, City, Country',
            'website' => 'https://example.com',
        ]);
    }
}
