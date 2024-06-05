<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class ScrapeGoogleSearch extends Command
{
    protected $signature = 'scrape:google-search {keyword}';
    protected $description = 'Scrape Google search results for a given keyword';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $keyword = $this->argument('keyword');
        $url = "https://www.google.com/search?hl=tr&safe=off&q=" . urlencode(trim($keyword));

        $driver = RemoteWebDriver::create(
            'http://localhost:9515', // Selenium server address
            ['platform' => 'WINDOWS', 'browserName' => 'chrome']
        );

        $driver->get($url);

        // Wait until the results are loaded
        $driver->wait()->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::cssSelector('div.g')
            )
        );

        // Get all result elements
        $resultElements = $driver->findElements(WebDriverBy::cssSelector('div.g'));

        foreach ($resultElements as $position => $resultElement) {
            $titleElement = $resultElement->findElement(WebDriverBy::cssSelector('h3.LC20lb.DKV0Md'));
            $title = $titleElement ? $titleElement->getText() : 'Title not found';

            $urlElement = $resultElement->findElement(WebDriverBy::cssSelector('a'));
            $url = $urlElement ? $urlElement->getAttribute('href') : 'URL not found';

            echo "Position: " . ($position + 1) . PHP_EOL;
            echo "Title: $title" . PHP_EOL;
            echo "URL: $url" . PHP_EOL;
            echo PHP_EOL;
        }

        // Close the browser
        $driver->quit();
    }
}
