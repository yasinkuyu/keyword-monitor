<?php
// app/Http/Controllers/KeywordController.php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\Domain;

class KeywordController extends Controller
{

    public function index(Request $request)
    {
        $listKeywords = Keyword::paginate(10);

        return Inertia::render('Keywords/ListKeywords', [
            'listKeywords' => $listKeywords,
            'filters' => request()->all('sort', 'direction', 'domain_id'),
            'links' => $listKeywords->links(), 
        ]);
    }

    public function getKeywordPerformance(Request $request)
    {
        $domainId = $request->domain_id;
        // Örnek bir grafik verisi oluşturmak için rastgele veri oluşturuyoruz
        $chartData = [];
        for ($i = 0; $i < 30; $i++) {
            $chartData[] = [
                'date' => date('Y-m-d', strtotime("-$i days")),
                'position' => rand(1, 10)
            ];
        }

        return response()->json(['chartData' => $chartData]);
    }

    public function create()
    {
        $domains = Domain::orderBy('name', 'ASC')->get();

        return view('keywords.create', compact('domains'));
    }

    public function json(Request $request)
    {
        $query = $request->input('query');
        $keywords = Keyword::where('keyword', 'LIKE', "%$query%")->get();
        return response()->json($keywords);
    }

    // json için eklendi
    public function save(Request $request)
    {
        $request->validate([
            'keyword' => 'required|unique:keywords,keyword|max:255',
        ]);

        $keyword = Keyword::create($request->all());

        return response()->json(['keyword' => $keyword], 201);
    }

    public function store(Request $request)
    {

        $request->validate([
            'keyword' => 'required|unique:keywords,keyword',
            'domain_id' => 'required',
        ]);

        Keyword::create($request->all());

        return redirect()->route('keywords.index')
                        ->with('success','Keyword created successfully.');
    }

    public function edit(Keyword $keyword)
    {
        return view('keywords.edit',compact('keyword'));
    }

    public function update(Request $request, Keyword $keyword)
    {
        $request->validate([
            'keyword' => 'required|unique:keywords,keyword,'.$keyword->id,
        ]);

        $keyword->update($request->all());

        return redirect()->route('keywords.index')
                        ->with('success','Keyword updated successfully');
    }

    public function destroy(Keyword $keyword)
    {
        $keyword->delete();

        return redirect()->route('keywords.index')
                        ->with('success','Keyword deleted successfully');
    }
}
