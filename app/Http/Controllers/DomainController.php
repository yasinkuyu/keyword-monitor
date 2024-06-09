<?php
// app/Http/Controllers/DomainController.php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\KeywordPosition;
use App\Models\Keyword; 

class DomainController extends Controller
{

    public function index(Request $request)
    {
        $listDomains = Domain::paginate(10);

        return Inertia::render('Domains/ListDomains', [
            'listDomains' => $listDomains,
            'filters' => request()->all('sort', 'direction'),
            'links' => $listDomains->links(), 
        ]);
    }
   
    public function create()
    {
        return Inertia::render('Domains/CreateDomain');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:domains,name', 'max:255', 'regex:/^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/'],
        ]);

        Domain::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        return redirect()->route('domains.create');
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

        return redirect()->route('domains.create');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
    }
}
