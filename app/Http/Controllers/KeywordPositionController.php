<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\KeywordPosition;
use App\Models\Domain;
use App\Models\Keyword; 
use App\Models\Language; 
use App\Models\Country; 
class KeywordPositionController extends Controller
{
      
    public function index(Request $request)
    {
        $keywordPositions = KeywordPosition::with(['domain', 'keyword'])->paginate(10);

        return Inertia::render('KeywordPositions/ListKeywordPositions', [
            'keywordPositions' => $keywordPositions,
            'filters' => request()->all('sort', 'direction'),
            'links' => $keywordPositions->links(), 
        ]);
    }

    public function report(Request $request, $keyword_id = null)
    {
        $start_date = $request->input('start_date') ? $request->input('start_date') : now()->subDays(30);
        $end_date = $request->input('end_date') ? $request->input('end_date') : now();

        $keyword = Keyword::find($keyword_id);

        $keywordPositions = KeywordPosition::with(['domain', 'keyword'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('keyword_id', $keyword_id)
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            });

        $labels = [];
        $data = [];
        foreach ($keywordPositions as $date => $positions) {
            $labels[] = $date; 
            $data[] = round($positions->avg('position')); 
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $keyword->keyword,
                    'data' => $data,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'pointStyle' => 'circle',
                    'pointRadius' => 5, // Küçük bir nokta boyutu
                    'pointHoverRadius' => 8 // Hover sırasında biraz büyüt
                ]
            ]
        ];

        $lastMonths = [];
        for ($i = 0; $i <= 10; $i++) {
            $month = date('F Y', strtotime("-$i months"));
            $startDate = date('Y-m-01', strtotime("-$i months"));
            $endDate = date('Y-m-t', strtotime("-$i months"));
            $lastMonths[] = [
                'name' => $month,
                'startDate' => $startDate,
                'endDate' => $endDate
            ];
        }

        return Inertia::render('KeywordPositions/ReportKeywordPosition', [
            'chartData' => $chartData,
            'startDate' => date('Y-m-d', strtotime($start_date)),
            'endDate' => date('Y-m-d', strtotime($end_date)),
            'keyword' => $keyword,
            'lastMonths' => $lastMonths,
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
        // Check if the keyword exists in the Keyword table, create it if not
        $keywordRow = Keyword::firstOrCreate(['keyword' => $keyword]);

        // Check if the domain exists in the Domain table, create it if not
        $domainRow = Domain::firstOrCreate(['name' => $domain]);

        // Update or create the KeywordPosition record
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
            'updated_at' => 'required|date',
        ]);

        $existingKeywordPosition = KeywordPosition::where('domain_id', $request->domain_id)
            ->where('keyword_id', $request->keyword_id)
            ->where('language', $request->language)
            ->where('country', $request->country)
            ->whereDate('created_at', now()->toDateString())
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
