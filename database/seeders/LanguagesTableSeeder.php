<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Uses upsert to make seeder idempotent - rerunning will update existing records.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'tr', 'name' => 'Türkçe'],
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'it', 'name' => 'Italian'],
            ['code' => 'pt', 'name' => 'Portuguese'],
            ['code' => 'ru', 'name' => 'Russian'],
            ['code' => 'ja', 'name' => 'Japanese'],
            ['code' => 'zh', 'name' => 'Chinese'],
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'nl', 'name' => 'Dutch'],
            ['code' => 'pl', 'name' => 'Polish'],
            ['code' => 'sv', 'name' => 'Swedish'],
            ['code' => 'ko', 'name' => 'Korean'],
        ];

        // Use upsert for idempotent seeding - updates if exists, inserts if not
        DB::table('languages')->upsert(
            $languages,
            ['code'], // unique key
            ['name']  // columns to update
        );
    }
}
