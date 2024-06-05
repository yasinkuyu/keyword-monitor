<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class ServiceGoogleSelenium
{
    public static function get($keywords, $domain, $country, $max_pages = 1)
    {
  
        // Anahtar kelimeleri yeni satırlara bölmek
        $keywords = explode("\n", $keywords);

        foreach ($keywords as $keyword) {
            
            $position = 0;

            for ($page = 1; $page <= $max_pages; $page++) {

                // Google arama URL'si oluşturma
                $url = "https://www.google.com/search?hl={$country}&num=100&q=" . urlencode(trim($keyword)) . ($page > 1 ? "&start=" . (100 * ($page - 1)) : "");
                
                $response = Http::withHeaders([ 
                    'Accept'=> '*/*', 
                    'Content-Type' => 'text/html; charset=utf-8',
                    'User-Agent'=> 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 
                ]) 
                ->get($url); 
            
                $html = $response->body();

                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);
                
                $resultBoxes = $xpath->query('//div[@id="main"]/div//a[@data-ved]/div/div[2]/div | //div[@id="main"]/div//a[@data-ved]/div/div//cite'); 
                foreach ($resultBoxes as $key => $resultBox) {

                    try {

                        $title = $xpath->query(".//text()", $resultBox)[0]->nodeValue;

                       // Başlık içinde domain geçiyorsa pozisyonu artır
                       if (strpos($title, $domain) !== false) {
                            $position = $key +1;
                            break;
                        }
                        
                    } catch (Exception $e) {
                       echo $e->getMessage();
                    }
                }
            }
        }

        return $position;
    }
}
