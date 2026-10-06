<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyApplicationController extends Controller
{
    public function index(Request $request, JobOffer $offer): View
    {
        $this->ensureOwnership($request, $offer);

        $applications = $offer->applications()
            ->with(['profile.user', 'profile.projects'])
            ->latest('submitted_at')
            ->get();

        return view('company.offers.applications', compact('offer', 'applications'));
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $this->ensureOwnership($request, $application->offer);

        $data = $request->validate([
            'status' => ['required', 'in:reviewed,shortlisted,interview,accepted,rejected'],
        ]);

        $application->update($data);

        return back()->with('success', 'Le statut de candidature a été mis à jour.');
    }

    private function ensureOwnership(Request $request, JobOffer $offer): void
    {
        abort_unless(
            $request->user()->companies()->whereKey($offer->company_id)->exists(),
            403
        );
    }
}
