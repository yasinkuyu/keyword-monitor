<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Language;
use App\Models\Country;
use Database\Seeders\LanguagesTableSeeder;
use Database\Seeders\CountriesTableSeeder;

class LanguageCountrySeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that languages seeder populates the database.
     */
    public function test_languages_seeder_populates_database(): void
    {
        // Run the seeder
        $this->seed(LanguagesTableSeeder::class);

        // Assert that languages exist
        $this->assertDatabaseCount('languages', 15);
        
        // Assert specific languages exist
        $this->assertDatabaseHas('languages', ['code' => 'en', 'name' => 'English']);
        $this->assertDatabaseHas('languages', ['code' => 'tr', 'name' => 'Türkçe']);
        $this->assertDatabaseHas('languages', ['code' => 'es', 'name' => 'Spanish']);
        $this->assertDatabaseHas('languages', ['code' => 'de', 'name' => 'German']);
    }

    /**
     * Test that countries seeder populates the database.
     */
    public function test_countries_seeder_populates_database(): void
    {
        // Run the seeder
        $this->seed(CountriesTableSeeder::class);

        // Assert that countries exist
        $this->assertDatabaseCount('countries', 20);
        
        // Assert specific countries exist
        $this->assertDatabaseHas('countries', ['code' => 'us', 'name' => 'United States']);
        $this->assertDatabaseHas('countries', ['code' => 'tr', 'name' => 'Turkiye']);
        $this->assertDatabaseHas('countries', ['code' => 'uk', 'name' => 'United Kingdom']);
        $this->assertDatabaseHas('countries', ['code' => 'de', 'name' => 'Germany']);
    }

    /**
     * Test that languages seeder is idempotent (can be rerun safely).
     */
    public function test_languages_seeder_is_idempotent(): void
    {
        // Run the seeder twice
        $this->seed(LanguagesTableSeeder::class);
        $this->seed(LanguagesTableSeeder::class);

        // Assert count is still correct (not doubled)
        $this->assertDatabaseCount('languages', 15);
    }

    /**
     * Test that countries seeder is idempotent (can be rerun safely).
     */
    public function test_countries_seeder_is_idempotent(): void
    {
        // Run the seeder twice
        $this->seed(CountriesTableSeeder::class);
        $this->seed(CountriesTableSeeder::class);

        // Assert count is still correct (not doubled)
        $this->assertDatabaseCount('countries', 20);
    }

    /**
     * Test that seeder updates existing records when name changes.
     */
    public function test_languages_seeder_updates_existing_records(): void
    {
        // Create a language with a different name
        Language::create(['code' => 'en', 'name' => 'Old English Name']);

        // Run the seeder
        $this->seed(LanguagesTableSeeder::class);

        // Assert the name was updated
        $this->assertDatabaseHas('languages', ['code' => 'en', 'name' => 'English']);
        $this->assertDatabaseMissing('languages', ['code' => 'en', 'name' => 'Old English Name']);
    }

    /**
     * Test that seeder updates existing records when name changes.
     */
    public function test_countries_seeder_updates_existing_records(): void
    {
        // Create a country with a different name
        Country::create(['code' => 'us', 'name' => 'Old USA Name']);

        // Run the seeder
        $this->seed(CountriesTableSeeder::class);

        // Assert the name was updated
        $this->assertDatabaseHas('countries', ['code' => 'us', 'name' => 'United States']);
        $this->assertDatabaseMissing('countries', ['code' => 'us', 'name' => 'Old USA Name']);
    }

    /**
     * Test that language code is unique.
     */
    public function test_language_code_is_unique(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Language::create(['code' => 'en', 'name' => 'English']);
        Language::create(['code' => 'en', 'name' => 'Another English']);
    }

    /**
     * Test that country code is unique.
     */
    public function test_country_code_is_unique(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Country::create(['code' => 'us', 'name' => 'United States']);
        Country::create(['code' => 'us', 'name' => 'Another United States']);
    }
}
