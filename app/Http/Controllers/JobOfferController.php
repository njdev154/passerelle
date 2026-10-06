<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    public function create(Request $request): View
    {
        return view('company.offers.create', ['company' => $request->user()->companies()->firstOrFail()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'min:80', 'max:8000'],
            'city' => ['required', 'string', 'max:100'],
            'work_mode' => ['required', 'in:on_site,hybrid,remote'],
            'contract_type' => ['required', 'in:internship,apprenticeship,first_job'],
            'required_level' => ['nullable', 'string', 'max:100'],
            'application_deadline' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $company = $request->user()->companies()->firstOrFail();
        $baseSlug = Str::slug($data['title']);
        $slug = $baseSlug;
        $number = 2;
        while (JobOffer::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$number++;
        }

        $company->offers()->create($data + ['slug' => $slug, 'status' => 'pending_review']);

        return redirect()->route('company.dashboard')->with('success', 'Votre offre a été envoyée pour vérification.');
    }

    public function index(Request $request): View
    {
        $offers = $this->publishedOffers($request)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('offers.index', compact('offers'));
    }

    public function show(JobOffer $offer): View
    {
        abort_unless($offer->status === 'published', 404);

        $hasApplied = auth()->check() && auth()->user()->studentProfile
            ? $offer->applications()->where('student_profile_id', auth()->user()->studentProfile->id)->exists()
            : false;

        return view('offers.show', compact('offer', 'hasApplied'));
    }

    public function featured(Request $request): \Illuminate\Support\Collection
    {
        return $this->publishedOffers($request)
            ->latest('published_at')
            ->limit(3)
            ->get();
    }

    private function publishedOffers(Request $request): Builder
    {
        return JobOffer::query()
            ->with('company')
            ->where('status', 'published')
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = trim((string) $request->string('q'));
                $query->where(function (Builder $matches) use ($term): void {
                    $matches->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('city'), function (Builder $query) use ($request): void {
                $query->where('city', 'like', '%'.trim((string) $request->string('city')).'%');
            });
    }
}
