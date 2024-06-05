<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecaptchaController extends Controller
{
    public function downloadRecaptchaJs()
    {
        // Recaptcha API dosyasının URL'i
        $recaptchaUrl = 'https://www.google.com/recaptcha/api.js?render=explicit';

        // Referer kontrolü ekleme
        $referer = 'https://seo.ai';

        // cURL oturumunu başlat
        $ch = curl_init();

        // cURL ayarlarını yapılandır
        curl_setopt($ch, CURLOPT_URL, $recaptchaUrl);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Dosyayı indir
        $recaptchaJs = curl_exec($ch);

        // cURL oturumunu kapat
        curl_close($ch);

        // İndirilen dosyayı tarayıcıya gönder
        return response($recaptchaJs)
            ->header('Content-Type', 'application/javascript');
    }
}
