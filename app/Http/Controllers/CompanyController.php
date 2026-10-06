<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->companies()->exists()) {
            return redirect()->route('company.dashboard');
        }

        return view('company.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sector' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $count = 2;
        while (Company::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$count++;
        }

        $request->user()->companies()->create($data + ['slug' => $slug]);

        return redirect()->route('company.dashboard')->with('success', 'Votre organisation est créée. Elle sera vérifiée avant que ses offres soient publiées.');
    }

    public function dashboard(Request $request): View
    {
        $company = $request->user()->companies()->withCount('offers')->firstOrFail();
        $offers = $company->offers()->withCount('applications')->latest()->get();

        return view('company.dashboard', compact('company', 'offers'));
    }
}
