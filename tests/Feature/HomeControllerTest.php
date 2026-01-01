<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Language;
use App\Models\Country;
use Database\Seeders\LanguagesTableSeeder;
use Database\Seeders\CountriesTableSeeder;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that HomeController loads languages from database.
     */
    public function test_home_controller_loads_languages_from_database(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Seed languages
        $this->seed(LanguagesTableSeeder::class);

        // Act as the user and visit home
        $response = $this->actingAs($user)->get('/home');

        // Check response is successful
        $response->assertStatus(200);

        // Assert that languages from database are passed to view
        $response->assertViewHas('languages', function ($languages) {
            return $languages->count() === 15 
                && $languages->where('code', 'en')->first()->name === 'English'
                && $languages->where('code', 'tr')->first()->name === 'Türkçe';
        });
    }

    /**
     * Test that HomeController loads countries from database.
     */
    public function test_home_controller_loads_countries_from_database(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Seed countries
        $this->seed(CountriesTableSeeder::class);

        // Act as the user and visit home
        $response = $this->actingAs($user)->get('/home');

        // Check response is successful
        $response->assertStatus(200);

        // Assert that countries from database are passed to view
        $response->assertViewHas('countries', function ($countries) {
            return $countries->count() === 20 
                && $countries->where('code', 'us')->first()->name === 'United States'
                && $countries->where('code', 'tr')->first()->name === 'Turkiye';
        });
    }

    /**
     * Test that newly added languages appear in home controller.
     */
    public function test_newly_added_languages_appear_in_home(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Seed initial languages
        $this->seed(LanguagesTableSeeder::class);

        // Add a new language
        Language::create(['code' => 'hi', 'name' => 'Hindi']);

        // Act as the user and visit home
        $response = $this->actingAs($user)->get('/home');

        // Assert the new language is included
        $response->assertViewHas('languages', function ($languages) {
            return $languages->count() === 16 
                && $languages->where('code', 'hi')->first()->name === 'Hindi';
        });
    }

    /**
     * Test that newly added countries appear in home controller.
     */
    public function test_newly_added_countries_appear_in_home(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Seed initial countries
        $this->seed(CountriesTableSeeder::class);

        // Add a new country
        Country::create(['code' => 'nz', 'name' => 'New Zealand']);

        // Act as the user and visit home
        $response = $this->actingAs($user)->get('/home');

        // Assert the new country is included
        $response->assertViewHas('countries', function ($countries) {
            return $countries->count() === 21 
                && $countries->where('code', 'nz')->first()->name === 'New Zealand';
        });
    }
}
