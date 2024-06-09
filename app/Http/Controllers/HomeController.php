<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Keyword;
use Auth;

class HomeController extends Controller
{
    public function index()
    {

        $keywords = Auth::user()->keywords()->orderBy('keyword', 'ASC')->get();
        $domains = Auth::user()->domains()->orderBy('name', 'ASC')->get();
        $languages = ['tr' => 'Türkçe', 'en' => 'English', 'es' => 'Spanish', 'de' => 'German'];
        $countries = ['tr' => 'Turkiye', 'us' => 'United States', 'uk' => 'United Kingdom', 'de' => 'Germany'];
        
        return view('home', compact('keywords', 'domains', 'countries', 'languages'));

    }
}
