<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\KeywordPosition;
use App\Models\Domain;
use App\Models\Keyword; 

class KeywordPositionController extends Controller
{
      
    public function index(Request $request)
    {
        $keywordPositions = KeywordPosition::with(['domain', 'keyword'])->paginate(2);

        return Inertia::render('KeywordPositions', [
            'keywordPositions' => $keywordPositions,
            'filters' => request()->all('sort', 'direction'),
            'links' => $keywordPositions->links(), 
        ]);

        
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $keywordPositions = KeywordPosition::with('domain', 'keyword')
            ->orderBy($sort, $direction)
            ->paginate();

        return Inertia::render('KeywordPositions', [
            'csrf_token' => Session::token(),
            'keywordPositions' => $keywordPositions,
            'sort' => [
                'column' => $sort,
                'direction' => $direction,
            ],
        ]);
    }
  
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $domain = $request->input('domain');
        $country = $request->input('country');
        $language = $request->input('language');
        $service = $request->input('service');

        if (!$keyword) {
            return response()->json(['error' => 'Enter the keyword.'], 404);
        }

        if (!$domain) {
            return response()->json(['error' => 'Enter the domain.'], 404);
        }

        if (!$country) {
            return response()->json(['error' => 'Enter the country.'], 404);
        }

        if (!$language) {
            return response()->json(['error' => 'Enter the language.'], 404);
        }

        if (!$service) {
            return response()->json(['error' => 'Select the service.'], 404);
        }

        try {

            switch ($service) {
                case 'google.selenium':
                    $position = \App\Helpers\ServiceGoogleSelenium::get(
                        $keyword,
                        $domain,
                        $country
                    );
                    break;
                case 'tools.seo.ai':
                    $position = \App\Helpers\ServiceToolsSeoAI::get(
                        $request->input('recaptcha_key'),
                        $keyword,
                        $domain,
                        $country
                    );
                    break;
                default:

            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Keyword tablosunda anahtar kelimenin olup olmadığını kontrol et
        $keywordRow = Keyword::firstOrCreate(['keyword' => $keyword]);

        // Domain tablosunda domainin olup olmadığını kontrol et
        $domainRow = Domain::firstOrCreate(['name' => $domain]);

        // KeywordPosition kaydını güncelle veya oluştur
        KeywordPosition::updateOrCreatePosition($keywordRow->id, $position, $domainRow->id, $country, $language);

        return response()->json(['position' => $position]);
    }
    
    public function json()
    {
        $keywordPositions = KeywordPosition::with('keyword')->with('domain')->orderBy('updated_at', 'desc')->get();

        foreach ($keywordPositions as $keywordPosition) {
            $keywordPosition->created_at_format = $keywordPosition->created_at->format('d/m/Y');
            $keywordPosition->updated_at_format = $keywordPosition->updated_at->format('d/m/Y H:i:s');
        }

        return response()->json($keywordPositions);
    }

    public function create()
    {
        $keywords = Keyword::orderBy('keyword', 'ASC')->get();
        $domains = Domain::orderBy('name', 'ASC')->get();
        $languages = ['tr' => 'Türkçe', 'en' => 'English', 'es' => 'Spanish', 'de' => 'German'];
        $countries = ['tr' => 'Turkiye', 'us' => 'United States', 'uk' => 'United Kingdom', 'de' => 'Germany'];
        
        return view('keyword-positions.create', compact('keywords', 'domains', 'countries', 'languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keyword_id' => 'required',
            'query_date' => 'required|date',
            'position' => 'required|numeric',
        ]);

        KeywordPosition::create($request->all());

        return redirect()->route('keyword-positions.index')
                        ->with('success', 'Keyword Position created successfully.');
    }

    public function edit(KeywordPosition $keywordPosition)
    {
        return view('keyword-positions.edit', compact('keywordPosition'));
    }

    public function update(Request $request, KeywordPosition $keywordPosition)
    {
        $request->validate([
            'keyword_id' => 'required',
            'query_date' => 'required|date',
            'position' => 'required|numeric',
        ]);

        $keywordPosition->update($request->all());

        return redirect()->route('keyword-positions.index')
                        ->with('success', 'Keyword Position updated successfully');
    }

    public function show(KeywordPosition $keywordPosition)
    {
        return view('keyword-positions.edit', compact('keywordPosition'));
    }

    public function destroy(KeywordPosition $keywordPosition)
    {
        $keywordPosition->delete();

        return redirect()->route('keyword-positions.index')
                        ->with('success', 'Keyword Position deleted successfully');
    }
}
