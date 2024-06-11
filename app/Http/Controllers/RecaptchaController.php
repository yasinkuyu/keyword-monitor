<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecaptchaController extends Controller
{
    public function index()
    {
        // URL of the Recaptcha API file
        $recaptchaUrl = 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit';

        // Add referer check
        $referer = 'https://seo.ai';

        // Start cURL session
        $ch = curl_init();

        // Configure cURL settings
        curl_setopt($ch, CURLOPT_URL, $recaptchaUrl);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Download the file
        $recaptchaJs = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        // Send the downloaded file to the browser
        return response($recaptchaJs)
            ->header('Content-Type', 'application/javascript');
    }
}
