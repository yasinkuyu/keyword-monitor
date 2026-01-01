<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Uses upsert to make seeder idempotent - rerunning will update existing records.
     */
    public function run(): void
    {
        $countries = [
            ['code' => 'tr', 'name' => 'Turkiye'],
            ['code' => 'us', 'name' => 'United States'],
            ['code' => 'uk', 'name' => 'United Kingdom'],
            ['code' => 'de', 'name' => 'Germany'],
            ['code' => 'fr', 'name' => 'France'],
            ['code' => 'it', 'name' => 'Italy'],
            ['code' => 'pt', 'name' => 'Portugal'],
            ['code' => 'es', 'name' => 'Spain'],
            ['code' => 'ru', 'name' => 'Russia'],
            ['code' => 'jp', 'name' => 'Japan'],
            ['code' => 'cn', 'name' => 'China'],
            ['code' => 'nl', 'name' => 'Netherlands'],
            ['code' => 'pl', 'name' => 'Poland'],
            ['code' => 'se', 'name' => 'Sweden'],
            ['code' => 'kr', 'name' => 'South Korea'],
            ['code' => 'ca', 'name' => 'Canada'],
            ['code' => 'au', 'name' => 'Australia'],
            ['code' => 'br', 'name' => 'Brazil'],
            ['code' => 'mx', 'name' => 'Mexico'],
            ['code' => 'in', 'name' => 'India'],
        ];

        // Use upsert for idempotent seeding - updates if exists, inserts if not
        DB::table('countries')->upsert(
            $countries,
            ['code'], // unique key
            ['name']  // columns to update
        );
    }
}
