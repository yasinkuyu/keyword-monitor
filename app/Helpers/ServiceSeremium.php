<?php

namespace App\Helpers;

use App\Exceptions\SeremiumAuthException;
use App\Exceptions\SeremiumException;
use App\Exceptions\SeremiumQuotaException;
use App\Exceptions\SeremiumRateLimitException;
use App\Exceptions\SeremiumServerException;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ServiceSeremium
{
    // Error states to distinguish from valid results
    const ERROR_AUTH_FAILED = 'AUTH_FAILED';

    const ERROR_QUOTA_EXCEEDED = 'QUOTA_EXCEEDED';

    const ERROR_RATE_LIMITED = 'RATE_LIMITED';

    const ERROR_SERVER_ERROR = 'SERVER_ERROR';

    const ERROR_INVALID_RESPONSE = 'INVALID_RESPONSE';

    const NOT_FOUND = 'NOT_FOUND';

    // Retry configuration
    const MAX_RETRIES = 3;

    const INITIAL_RETRY_DELAY = 1; // seconds

    /**
     * Get keyword position from Seremium API
     *
     * @param  string  $keyword  The search keyword
     * @param  string  $domain  The domain to search for
     * @param  string  $country  The country code
     * @param  string|null  $apiKey  The API key for authentication
     * @return int|string Returns position (1-based) or error constant
     *
     * @throws SeremiumException When provider failure occurs
     */
    public static function get($keyword, $domain, $country, $apiKey = null)
    {
        // Get API key from environment if not provided
        $apiKey = $apiKey ?? env('SEREMIUM_API_KEY');

        if (! $apiKey) {
            self::logDebug('Seremium API key not configured');
            throw new SeremiumException('Seremium API key not configured. Please set SEREMIUM_API_KEY in your environment.');
        }

        $url = env('SEREMIUM_API_URL', 'https://api.seremium.com/v1/search');

        $params = [
            'q' => $keyword,
            'location' => strtoupper($country),
            'num' => 100,
            'engine' => 'google',
        ];

        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $startTime = microtime(true);

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer '.$apiKey,
                        'Accept' => 'application/json',
                        'User-Agent' => 'KeywordMonitor/1.0',
                    ])
                    ->get($url, $params);

                $latency = round((microtime(true) - $startTime) * 1000, 2);
                $statusCode = $response->status();

                self::logDebug('Seremium API request', [
                    'status_code' => $statusCode,
                    'latency_ms' => $latency,
                    'attempt' => $attempt + 1,
                ]);

                // Handle different HTTP status codes
                if ($statusCode === 200) {
                    return self::parseResponse($response->json(), $domain);
                } elseif ($statusCode === 401 || $statusCode === 403) {
                    self::logDebug("Authentication failed with status $statusCode");
                    throw new SeremiumAuthException("Authentication failed. Please check your Seremium API key. (HTTP $statusCode)");
                } elseif ($statusCode === 402) {
                    self::logDebug('Quota exceeded');
                    throw new SeremiumQuotaException('API quota exceeded. Please upgrade your Seremium plan or wait for quota reset. (HTTP 402)');
                } elseif ($statusCode === 429) {
                    // Rate limited - retry with backoff
                    $attempt++;
                    if ($attempt < self::MAX_RETRIES) {
                        $delay = self::calculateBackoff($attempt);
                        self::logDebug("Rate limited, retrying in {$delay}s", ['attempt' => $attempt]);
                        sleep($delay);

                        continue;
                    }
                    throw new SeremiumRateLimitException("Rate limit exceeded after {$attempt} attempts. Please try again later. (HTTP 429)");
                } elseif ($statusCode >= 500) {
                    // Server error - retry with backoff
                    $attempt++;
                    if ($attempt < self::MAX_RETRIES) {
                        $delay = self::calculateBackoff($attempt);
                        self::logDebug("Server error (HTTP {$statusCode}), retrying in {$delay}s", ['attempt' => $attempt]);
                        sleep($delay);

                        continue;
                    }
                    throw new SeremiumServerException("Seremium server error after {$attempt} attempts. (HTTP {$statusCode})");
                } else {
                    // Other HTTP errors
                    self::logDebug('Unexpected HTTP status', [
                        'status_code' => $statusCode,
                        'response_preview' => substr($response->body(), 0, 200),
                    ]);
                    throw new SeremiumException("Unexpected response from Seremium API. (HTTP {$statusCode})");
                }

            } catch (Exception $e) {
                // If it's our own Seremium exception (business logic), rethrow immediately
                if ($e instanceof SeremiumException) {
                    throw $e;
                }

                // Network/connection errors - retry
                $lastException = $e;
                $attempt++;

                if ($attempt < self::MAX_RETRIES) {
                    $delay = self::calculateBackoff($attempt);
                    self::logDebug("Connection error, retrying in {$delay}s", [
                        'attempt' => $attempt,
                        'error' => $e->getMessage(),
                    ]);
                    sleep($delay);

                    continue;
                }

                throw new SeremiumException("Failed to connect to Seremium API after {$attempt} attempts: ".$e->getMessage(), 0, $e);
            }
        }

        // Should not reach here, but just in case
        throw $lastException ?? new SeremiumException("Failed to get position from Seremium after {$attempt} attempts");
    }

    /**
     * Parse the API response and extract position
     *
     * @param  array|null  $data  The decoded JSON response
     * @param  string  $domain  The domain to search for
     * @return int Position (1-based) or 0 for not found
     *
     * @throws Exception When response is invalid
     */
    private static function parseResponse($data, $domain)
    {
        if (! is_array($data)) {
            self::logDebug('Invalid response format', ['type' => gettype($data)]);
            throw new SeremiumException('Invalid response format from Seremium API');
        }

        // Check for API-level errors in the response
        if (isset($data['error'])) {
            $errorMsg = $data['error']['message'] ?? $data['error'];
            self::logDebug('API returned error', ['error' => $errorMsg]);
            throw new SeremiumException("Seremium API error: {$errorMsg}");
        }

        // Try different possible response structures
        $results = null;

        // Structure 1: { "organic_results": [...] }
        if (isset($data['organic_results']) && is_array($data['organic_results'])) {
            $results = $data['organic_results'];
        }
        // Structure 2: { "results": [...] }
        elseif (isset($data['results']) && is_array($data['results'])) {
            $results = $data['results'];
        }
        // Structure 3: { "data": { "results": [...] } }
        elseif (isset($data['data']['results']) && is_array($data['data']['results'])) {
            $results = $data['data']['results'];
        }
        // Structure 4: Direct array of results
        elseif (isset($data[0]) && is_array($data[0])) {
            $results = $data;
        }

        if ($results === null) {
            self::logDebug('Could not find results in response', [
                'available_keys' => array_keys($data),
            ]);
            throw new SeremiumException('Could not parse results from Seremium API response');
        }

        self::logDebug('Parsing results', ['result_count' => count($results)]);

        // Search for the domain in results
        foreach ($results as $index => $result) {
            // Try different possible field names for the URL/link
            $resultUrl = $result['link'] ?? $result['url'] ?? $result['displayed_link'] ?? null;

            if ($resultUrl && self::domainMatches($resultUrl, $domain)) {
                $position = $index + 1; // 1-based position
                self::logDebug('Domain found', [
                    'domain' => $domain,
                    'position' => $position,
                ]);

                return $position;
            }
        }

        // Domain not found in results - this is valid, not an error
        self::logDebug('Domain not found in results', [
            'domain' => $domain,
            'result_count' => count($results),
        ]);

        return 0; // Return 0 only when domain is legitimately not found
    }

    /**
     * Check if a URL matches the target domain
     *
     * @param  string  $url  The URL from search results
     * @param  string  $domain  The domain to match
     * @return bool
     */
    private static function domainMatches($url, $domain)
    {
        // Extract domain from URL
        $parsedUrl = parse_url($url);
        $urlDomain = $parsedUrl['host'] ?? '';

        // Remove www. prefix for comparison
        $urlDomain = preg_replace('/^www\./', '', $urlDomain);
        $targetDomain = preg_replace('/^www\./', '', $domain);

        return strcasecmp($urlDomain, $targetDomain) === 0;
    }

    /**
     * Calculate exponential backoff delay
     *
     * @param  int  $attempt  Attempt number (1-based)
     * @return int Delay in seconds
     */
    private static function calculateBackoff($attempt)
    {
        // Exponential backoff: 1s, 2s, 4s, 8s, ... (capped at 30s)
        return min(self::INITIAL_RETRY_DELAY * pow(2, $attempt - 1), 30);
    }

    /**
     * Log debug information if debug mode is enabled
     *
     * @param  string  $message  Log message
     * @param  array  $context  Additional context (secrets will be filtered)
     */
    private static function logDebug($message, $context = [])
    {
        // Only log if debug mode is enabled
        if (! env('SEREMIUM_DEBUG', false)) {
            return;
        }

        // Filter out sensitive data
        $safeContext = self::filterSensitiveData($context);

        Log::debug("Seremium: {$message}", $safeContext);
    }

    /**
     * Filter sensitive data from context before logging
     *
     * @param  array  $context  Context data
     * @return array Filtered context
     */
    private static function filterSensitiveData($context)
    {
        $sensitiveKeys = ['api_key', 'apikey', 'authorization', 'token', 'password', 'secret'];

        foreach ($context as $key => $value) {
            $lowerKey = strtolower($key);
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (str_contains($lowerKey, $sensitiveKey)) {
                    $context[$key] = '[REDACTED]';
                    break;
                }
            }

            // Recursively filter nested arrays
            if (is_array($value)) {
                $context[$key] = self::filterSensitiveData($value);
            }
        }

        return $context;
    }
}
