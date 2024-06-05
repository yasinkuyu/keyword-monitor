<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Keyword;

class HomeController extends Controller
{
    public function index()
    {

        $keywords = Keyword::orderBy('keyword', 'ASC')->get();
        $domains = Domain::orderBy('name', 'ASC')->get();
        $languages = ['tr' => 'Türkçe', 'en' => 'English', 'es' => 'Spanish', 'de' => 'German'];
        $countries = ['tr' => 'Turkiye', 'us' => 'United States', 'uk' => 'United Kingdom', 'de' => 'Germany'];
        
        return view('home', compact('keywords', 'domains', 'countries', 'languages'));

    }
}
