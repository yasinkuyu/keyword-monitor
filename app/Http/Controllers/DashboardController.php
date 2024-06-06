<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\KeywordPosition;
use App\Models\Domain;
use App\Models\Keyword; 
use Session;

class DashboardController extends Controller
{
      
    public function index()
    {
        
        $keywordCount = Keyword::count();
        $domainCount = Domain::count();
        $positionCount = KeywordPosition::count();

        return Inertia::render("Dashboard", [
            'keywordCount' => $keywordCount,
            'domainCount' => $domainCount,
            'positionCount' => $positionCount,
            'csrf_token' => Session::token(),
        ]);
        
    }
   
}
