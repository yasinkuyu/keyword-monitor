<?php
// app/Http/Controllers/KeywordController.php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\Domain;
use Auth;

class KeywordController extends Controller
{

    public function index(Request $request)
    {
        $listKeywords = Auth::user()->keywords()->with(['domain'])->paginate(10);

        return Inertia::render('Keywords/ListKeywords', [
            'listKeywords' => $listKeywords,
            'filters' => request()->all('sort', 'direction', 'domain_id'),
            'links' => $listKeywords->links(), 
        ]);
    }

    public function create()
    {
        $listDomains = Auth::user()->domains()->orderBy('name', 'ASC')->get();

        return Inertia::render('Keywords/CreateKeyword', ['listDomains' => $listDomains]);
    }

    public function json(Request $request)
    {

        return Auth::user()->keywords()->with(['domain'])
                ->orderBy('keyword')
                ->where('keyword', 'like', '%' . $request->search . '%')
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($keyword) => [
                    'id' => $keyword->id,
                    'name' => $keyword->keyword,
                    'desc' => $keyword->domain->name,
                ]);

    }

    // for ajax 
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
            'keyword' => 'required|unique:keywords,keyword,NULL,id,domain_id,'.$request->input('domain_id'),
            'domain_id' => 'required',
        ]);

        Keyword::create($request->all());

        return redirect()->route('keywords.create');
    }

    public function update(Request $request, Keyword $keyword)
    {
        $request->validate([
            'keyword' => 'required|unique:keywords,keyword,'.$keyword->id,
        ]);

        $keyword->update($request->all());

    }

    public function destroy(Keyword $keyword)
    {
        $keyword->delete();
    }
}
