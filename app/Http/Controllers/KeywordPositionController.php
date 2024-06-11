<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\KeywordPosition;
use App\Models\Domain;
use App\Models\Keyword; 
use App\Models\Language; 
use App\Models\Country; 
use Auth;

class KeywordPositionController extends Controller
{
      
    public function index(Request $request)
    {
        $keywordPositions = Auth::user()->positions()->with(['domain', 'keyword'])
            ->orderBy('keyword_positions.updated_at', 'DESC')
            ->paginate(10);

        return Inertia::render('KeywordPositions/ListKeywordPositions', [
            'keywordPositions' => $keywordPositions,
            'filters' => request()->all('sort', 'direction'),
            'links' => $keywordPositions->links(), 
        ]);
    }

    public function json()
    {
        $keywordPositions = Auth::user()->positions()->with(['domain', 'keyword'])->orderBy('updated_at', 'DESC')->paginate(10);

        return response()->json([
            'keywordPositions' => $keywordPositions,
            'filters' => request()->all('sort', 'direction'),
            'links' => $keywordPositions->links(), 
        ]);
    }

    public function create()
    {
        $keywords = Auth::user()->keywords()->orderBy('keyword', 'ASC')->get();
        $domains = Auth::user()->domains()->orderBy('name', 'ASC')->get();
        $languages = Language::orderBy('name', 'ASC')->get();
        $countries = Country::orderBy('name', 'ASC')->get();
        
        return Inertia::render('KeywordPositions/CreateKeywordPosition', ['listKeywords' => $keywords, 'listDomains' => $domains, 'listLanguages' => $languages, 'listCountries' => $countries]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain_id' => 'required',
            'keyword_id' => 'required', 
            'language' => 'required',
            'country' => 'required',
            'position' => 'required|numeric',
            'created_at' => 'required|date',
        ]);

        $existingKeywordPosition = KeywordPosition::where('domain_id', $request->domain_id)
            ->where('keyword_id', $request->keyword_id)
            ->where('language', $request->language)
            ->where('country', $request->country)
            ->whereDate('created_at', $request->created_at)
            ->first();

        if ($existingKeywordPosition) {
            $existingKeywordPosition->update($request->all());
        } else {
            KeywordPosition::create($request->all());
        }

        return redirect()->route('keyword-positions.create');
    }

    public function edit(KeywordPosition $keywordPosition)
    {
        return view('keyword-positions.edit', compact('keywordPosition'));
    }

    public function update(Request $request, KeywordPosition $keywordPosition)
    {
        $request->validate([
            'keyword_id' => 'required',
            'updated_at' => 'required|date',
            'position' => 'required|numeric',
        ]);

        $keywordPosition->update($request->all());

        return redirect()->route('keyword-positions.index');
    }

    public function show(KeywordPosition $keywordPosition)
    {
        return view('keyword-positions.edit', compact('keywordPosition'));
    }

    public function destroy(KeywordPosition $keywordPosition)
    {
        $keywordPosition->delete();
    }
}
