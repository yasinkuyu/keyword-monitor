<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\KeywordPosition;
use App\Models\Domain;
use App\Models\Keyword; 
use App\Models\Service; 
use Session;
use Auth;

class DashboardController extends Controller
{
      
    public function index()
    {
        
        $keywordCount = Auth::user()->keywords()->count();
        $domainCount = Auth::user()->domains()->count();
        $positionCount = Auth::user()->positions()->count();

        $services = Service::all();

        return Inertia::render("Dashboard", [
            'keywordCount' => $keywordCount,
            'domainCount' => $domainCount,
            'positionCount' => $positionCount,
            'csrf_token' => Session::token(),
            'services' => $services,
        ]);
        
    }
   
}
