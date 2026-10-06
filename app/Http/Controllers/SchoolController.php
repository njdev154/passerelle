<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InternshipRecord;
use App\Models\School;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->schools()->exists()) {
            return redirect()->route('school.dashboard');
        }

        return view('school.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'city' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $base = Str::slug($data['name']);
        $slug = $base;
        $number = 2;
        while (School::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$number++;
        }

        $request->user()->schools()->create($data + ['slug' => $slug]);

        return redirect()->route('school.dashboard')->with('success', 'Votre établissement est créé. Vous pouvez commencer le suivi des stages.');
    }

    public function dashboard(Request $request): View
    {
        $school = $request->user()->schools()->firstOrFail();
        $records = $school->internships()->with(['profile.user', 'company'])->latest()->get();

        return view('school.dashboard', compact('school', 'records'));
    }

    public function createInternship(Request $request): View
    {
        return view('school.internships.create', [
            'school' => $request->user()->schools()->firstOrFail(),
            'companies' => Company::query()->where('verification_status', 'verified')->orderBy('name')->get(),
        ]);
    }

    public function storeInternship(Request $request): RedirectResponse
    {
        $school = $request->user()->schools()->firstOrFail();
        $data = $request->validate([
            'student_email' => ['required', 'email'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'supervisor_name' => ['nullable', 'string', 'max:150'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', 'in:planned,agreement_pending,in_progress,completed,cancelled'],
            'objectives' => ['nullable', 'string', 'max:3000'],
            'school_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $profile = StudentProfile::query()->whereHas('user', fn ($query) => $query->where('email', $data['student_email']))->first();
        if (! $profile) {
            return back()->withInput()->withErrors(['student_email' => 'Aucun profil étudiant ne correspond à cette adresse e-mail.']);
        }

        $school->internships()->create(collect($data)->except('student_email')->all() + ['student_profile_id' => $profile->id]);

        return redirect()->route('school.dashboard')->with('success', 'Le suivi du stage a été ajouté.');
    }

    public function updateInternship(Request $request, InternshipRecord $internship): RedirectResponse
    {
        abort_unless($request->user()->schools()->whereKey($internship->school_id)->exists(), 403);

        $data = $request->validate([
            'status' => ['required', 'in:planned,agreement_pending,in_progress,completed,cancelled'],
            'school_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $internship->update($data);

        return back()->with('success', 'Le suivi a été mis à jour.');
    }
}
