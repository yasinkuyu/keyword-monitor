<?php

namespace Tests\Unit;

use App\Helpers\ServiceSeremium;
use Exception;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ServiceSeremiumTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock environment variables for testing
        putenv('SEREMIUM_API_KEY=test_api_key_12345');
        putenv('SEREMIUM_API_URL=https://api.seremium.com/v1/search');
        putenv('SEREMIUM_DEBUG=false');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up
        putenv('SEREMIUM_API_KEY');
        putenv('SEREMIUM_API_URL');
        putenv('SEREMIUM_DEBUG');
    }

    /** @test */
    public function it_throws_exception_when_api_key_is_not_configured()
    {
        putenv('SEREMIUM_API_KEY=');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Seremium API key not configured');

        ServiceSeremium::get('test keyword', 'example.com', 'us');
    }

    /** @test */
    public function it_returns_correct_position_for_successful_response()
    {
        // Mock successful API response with organic_results structure
        $mockResponse = [
            'organic_results' => [
                ['link' => 'https://other-site.com', 'title' => 'Other Site'],
                ['link' => 'https://example.com/page', 'title' => 'Example Page'],
                ['link' => 'https://another-site.com', 'title' => 'Another Site'],
            ],
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200),
        ]);

        $position = ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');

        $this->assertEquals(2, $position);
    }

    /** @test */
    public function it_returns_zero_when_domain_not_found()
    {
        // Mock successful API response but domain not in results
        $mockResponse = [
            'organic_results' => [
                ['link' => 'https://other-site.com', 'title' => 'Other Site'],
                ['link' => 'https://another-site.com', 'title' => 'Another Site'],
            ],
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200),
        ]);

        $position = ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');

        $this->assertEquals(0, $position);
    }

    /** @test */
    public function it_throws_exception_for_authentication_error_401()
    {
        Http::fake([
            '*' => Http::response(['error' => 'Unauthorized'], 401),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Authentication failed');

        ServiceSeremium::get('test keyword', 'example.com', 'us', 'invalid_key');
    }

    /** @test */
    public function it_throws_exception_for_authentication_error_403()
    {
        Http::fake([
            '*' => Http::response(['error' => 'Forbidden'], 403),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Authentication failed');

        ServiceSeremium::get('test keyword', 'example.com', 'us', 'invalid_key');
    }

    /** @test */
    public function it_throws_exception_for_quota_exceeded_402()
    {
        Http::fake([
            '*' => Http::response(['error' => 'Payment required'], 402),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('quota exceeded');

        ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');
    }

    /** @test */
    public function it_handles_different_response_structures()
    {
        // Test with 'results' instead of 'organic_results'
        $mockResponse = [
            'results' => [
                ['url' => 'https://example.com', 'title' => 'Example'],
            ],
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200),
        ]);

        $position = ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');

        $this->assertEquals(1, $position);
    }

    /** @test */
    public function it_handles_nested_data_structure()
    {
        // Test with nested data structure
        $mockResponse = [
            'data' => [
                'results' => [
                    ['link' => 'https://other.com'],
                    ['link' => 'https://example.com'],
                ],
            ],
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200),
        ]);

        $position = ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');

        $this->assertEquals(2, $position);
    }

    /** @test */
    public function it_matches_domain_with_www_prefix()
    {
        $mockResponse = [
            'organic_results' => [
                ['link' => 'https://www.example.com/page'],
            ],
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200),
        ]);

        // Should match even without www
        $position = ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');
        $this->assertEquals(1, $position);

        // Should also match when searching with www
        $position2 = ServiceSeremium::get('test keyword', 'www.example.com', 'us', 'test_key');
        $this->assertEquals(1, $position2);
    }

    /** @test */
    public function it_throws_exception_for_invalid_response_format()
    {
        Http::fake([
            '*' => Http::response('invalid json response', 200),
        ]);

        $this->expectException(Exception::class);

        ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');
    }

    /** @test */
    public function it_throws_exception_when_response_has_error_field()
    {
        $mockResponse = [
            'error' => [
                'message' => 'Invalid search parameters',
            ],
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid search parameters');

        ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');
    }

    /** @test */
    public function it_does_not_return_position_zero_for_server_errors()
    {
        Http::fake([
            '*' => Http::response(['error' => 'Internal Server Error'], 500),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('server error');

        // Should throw exception, not return 0
        ServiceSeremium::get('test keyword', 'example.com', 'us', 'test_key');
    }
}
