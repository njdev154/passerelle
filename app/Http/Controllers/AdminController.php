<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $companies = Company::query()
            ->where('verification_status', 'pending')
            ->with('owner')
            ->latest()
            ->get();

        $offers = JobOffer::query()
            ->where('status', 'pending_review')
            ->with('company')
            ->latest()
            ->get();

        $messages = ContactMessage::query()->latest()->get();

        return view('admin.dashboard', compact('companies', 'offers', 'messages'));
    }

    public function verifyCompany(Company $company): RedirectResponse
    {
        $company->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return back()->with('success', $company->name.' est désormais une entreprise vérifiée.');
    }

    public function rejectCompany(Company $company): RedirectResponse
    {
        $company->update(['verification_status' => 'rejected']);

        return back()->with('success', 'La demande de '.$company->name.' a été refusée.');
    }

    public function publishOffer(JobOffer $offer): RedirectResponse
    {
        if ($offer->company->verification_status !== 'verified') {
            return back()->withErrors(['offer' => 'Vérifiez d’abord l’entreprise avant de publier son offre.']);
        }

        $offer->update(['status' => 'published', 'published_at' => now()]);

        return back()->with('success', 'L’offre « '.$offer->title.' » est publiée.');
    }

    public function archiveOffer(JobOffer $offer): RedirectResponse
    {
        $offer->update(['status' => 'archived']);

        return back()->with('success', 'L’offre a été retirée de la file de publication.');
    }

    public function markMessageRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['status' => 'read']);

        return back()->with('success', 'Le message a été marqué comme traité.');
    }
}
