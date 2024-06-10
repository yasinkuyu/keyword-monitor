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



use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KeywordSearchController extends Controller
{
   
    public function report(Request $request, $keyword_id = null)
    {
        $start_date = $request->input('start_date') ?: now()->subDays(30);
        $end_date = $request->input('end_date') ?: now();

        $keyword = Keyword::find($keyword_id);
        $keywordPositions = Auth::user()->positions()->with(['domain', 'keyword'])
            ->whereBetween('keyword_positions.created_at', [$start_date, $end_date])
            ->where('keyword_positions.keyword_id', $keyword_id)
            ->get()
            ->groupBy(fn($item) => $item->domain->name . '-' . $item->country . '-' . $item->language);

        $labels = [];
        $datasets = [];
        $colors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(231, 233, 237, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(104, 132, 245, 1)'
        ];
        $backgroundColors = [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(231, 233, 237, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(104, 132, 245, 0.2)'
        ];


        $colorIndex = 0;

        foreach ($keywordPositions as $groupKey => $positions) {
            list($domain_id, $country, $language) = explode('-', $groupKey);
            $groupLabel = "{$domain_id}. {$country} {$language}";

            if (empty($labels)) {
                foreach ($positions as $position) {
                    $labels[] = $position->created_at->format('d M');
                }
            }

            $data = [];
            foreach ($positions as $position) {
                $data[] = round($position->position);
            }

            $datasets[] = [
                'label' => $groupLabel,
                'data' => $data,
                'borderColor' => $colors[$colorIndex % count($colors)],
                'backgroundColor' => $backgroundColors[$colorIndex % count($backgroundColors)],
                'pointStyle' => 'circle',
                'borderWidth' => 1.7,
                'pointRadius' => 4,
                'pointHoverRadius' => 8
            ];

            $colorIndex++;
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => $datasets
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

        $domain_id = $request->input('domain_id');
        $keyword_id = $request->input('keyword_id');
        $country = $request->input('country');
        $language = $request->input('language');
        $service = $request->input('service');

        if (!$domain_id) {
            return response()->json(['error' => 'Enter the domain.'], 404);
        }

        if (!$keyword_id) {
            return response()->json(['error' => 'Enter the keyword.'], 404);
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

        $domainRow = Domain::find($domain_id);
        $keywordRow = Keyword::find($keyword_id);

        $domain = $domainRow->name;
        $keyword = $keywordRow->keyword;    

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

        // Check if the domain exists in the Domain table, create it if not
        // $domainRow = Domain::firstOrCreate(['name' => $domain, 'user_id' => Auth::id()]);

        // Check if the keyword exists in the Keyword table, create it if not
        // $keywordRow = Keyword::firstOrCreate(['keyword' => $keyword, 'domain_id' => $domainRow->id]);

        // Update or create the KeywordPosition record
        KeywordPosition::updateOrCreatePosition($keywordRow->id, $position, $domainRow->id, $country, $language);

        return response()->json(['position' => $position]);
    }
    
}
