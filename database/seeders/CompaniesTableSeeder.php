<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        Company::create([
            'name' => 'Example Company',
            'email' => 'example@example.com', // Ensure this is unique
            'phone' => '123-456-7890',
            'address' => '123 Main St',
            'website' => 'https://example.com',
        ]);
    }
}