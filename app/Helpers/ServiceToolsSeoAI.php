<?php
namespace App\Helpers;

use Exception;

class ServiceToolsSeoAI
{
    public static function get($g_recaptcha_response, $keyword, $domain, $country)
    {

        $url = 'https://tools.seo.ai/api/rank-checker';

        $data = array(
            'g-recaptcha-response' => $g_recaptcha_response,
            'keyword' => $keyword,
            'domain' => $domain,
            'country' => $country,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Referer: https://seo.ai/tools/keyword-rank-checker',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        $result = json_decode($response, true);
     
        if (isset($result['data']['position'])) {
            return $result['data']['position'];
        }

        throw new Exception('Failed to get position from Google.');
    }
}
