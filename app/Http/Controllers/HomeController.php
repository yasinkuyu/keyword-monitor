<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Keyword;
use App\Models\Language;
use App\Models\Country;
use Auth;

class HomeController extends Controller
{
    public function index()
    {

        $keywords = Auth::user()->keywords()->orderBy('keyword', 'ASC')->get();
        $domains = Auth::user()->domains()->orderBy('name', 'ASC')->get();
        $languages = Language::orderBy('name', 'ASC')->get();
        $countries = Country::orderBy('name', 'ASC')->get();
        
        return view('home', compact('keywords', 'domains', 'countries', 'languages'));

    }
}
