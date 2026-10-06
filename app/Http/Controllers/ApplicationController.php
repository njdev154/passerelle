<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(Request $request, JobOffer $offer): RedirectResponse
    {
        abort_unless($offer->status === 'published', 404);

        $profile = $request->user()->studentProfile;
        if (! $profile || $profile->completion_percentage < 60) {
            return redirect()->route('student.profile.edit')->withErrors([
                'profile' => 'Complétez votre profil étudiant avant de candidater.',
            ]);
        }

        $data = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);

        $application = $offer->applications()->firstOrCreate(
            ['student_profile_id' => $profile->id],
            $data + ['status' => 'submitted', 'submitted_at' => now()]
        );

        if (! $application->wasRecentlyCreated) {
            return back()->withErrors(['application' => 'Vous avez déjà candidaté à cette offre.']);
        }

        return redirect()->route('dashboard')->with('success', 'Votre candidature a été envoyée à l’entreprise.');
    }
}
