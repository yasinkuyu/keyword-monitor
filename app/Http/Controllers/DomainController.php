<?php
// app/Http/Controllers/DomainController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\KeywordPosition;
use App\Models\Keyword; 

class DomainController extends Controller
{
    public function index()
    {

        $sortField = request('sort', 'id');
        $sortOrder = request('direction', 'asc');

        $domains = Domain::orderBy($sortField, $sortOrder)->paginate(10);

        return view('domains.index', compact('domains'));
    }

    public function report(Request $request)
    {
        $domain = Domain::find($request->id);

        if (!$domain) {
            return response()->json(['error' => 'Domain not found'], 404);
        }

        $keywords = $domain->keywords;
        
        print_r($keywords);die();
        
        $chartData = [];

        foreach ($keywords as $keyword) {
            print_r($keyword);
            $positions = KeywordPosition::where("domain_id", $keyword->domain_id)
                ->where("keyword_id", $keyword->id)
                ->where('date', '>=', now()->subDays(30))
                ->orderBy('date')
                ->pluck('position')
                ->toArray();

            // Eğer bu anahtar kelimenin son 30 gün içinde pozisyon verisi yoksa, varsayılan olarak 0 ekleyin
            if (count($positions) < 30) {
                $missingDaysCount = 30 - count($positions);
                $missingPositions = array_fill(0, $missingDaysCount, 0);
                $positions = array_merge($missingPositions, $positions);
            }

            // Anahtar kelimenin ülke, dil ve domain bilgilerini al
            $country = $keyword->country;
            $language = $keyword->language;

            // Veriyi chartData array'ine ekle
            $chartData[] = [
                'keyword' => $keyword->name,
                'positions' => $positions,
                'country' => $country,
                'language' => $language,
                'domain' => $domain->name
            ];
        }

        // Ülke, dil ve domain'e göre gruplayın
        $groupedChartData = collect($chartData)->groupBy(['country', 'language', 'domain']);
        
        print_r($groupedChartData);
        
        return response()->json([
            'keywords' => $groupedChartData
        ]);
    }

    public function create()
    {
        return view('domains.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:domains,name', 'max:255'],
        ]);

        Domain::create($request->all());

        return redirect()->route('domains.index')
                         ->with('success', 'Domain created successfully.');
    }

    public function edit(Domain $domain)
    {
        return view('domains.edit', compact('domain'));
    }

    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'name' => ['required', 'unique:domains,name,'.$domain->id, 'max:255'],
        ]);

        $domain->update($request->all());

        return redirect()->route('domains.index')
                        ->with('success','Domain updated successfully');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->route('domains.index')
                        ->with('success','Domain deleted successfully');
    }
}
