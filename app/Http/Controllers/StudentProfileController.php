<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $profile = $request->user()->studentProfile;

        return view('student.profile', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'headline' => ['nullable', 'string', 'max:160'],
            'biography' => ['nullable', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'level_of_study' => ['required', 'string', 'max:100'],
            'field_of_study' => ['required', 'string', 'max:150'],
            'availability_date' => ['nullable', 'date'],
            'profile_visibility' => ['nullable', 'boolean'],
        ]);

        $data['profile_visibility'] = $request->boolean('profile_visibility');
        $filled = collect(['headline', 'biography', 'city', 'level_of_study', 'field_of_study'])->filter(fn (string $field): bool => filled($data[$field] ?? null))->count();
        $data['completion_percentage'] = (int) round(($filled / 5) * 100);

        $request->user()->studentProfile()->updateOrCreate(['user_id' => $request->user()->id], $data);

        return redirect()->route('student.profile.edit')->with('success', 'Votre profil est enregistré.');
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $profile = $request->user()->studentProfile;
        abort_unless($profile, 403, 'Complétez d’abord votre profil.');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['required', 'string', 'min:30', 'max:2000'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'repository_url' => ['nullable', 'url', 'max:255'],
            'visibility' => ['nullable', 'boolean'],
        ]);

        $profile->projects()->create($data + ['visibility' => $request->boolean('visibility')]);

        return back()->with('success', 'Votre projet a été ajouté au portfolio.');
    }

    public function destroyProject(Request $request, Project $project): RedirectResponse
    {
        abort_unless($project->student_profile_id === $request->user()->studentProfile?->id, 403);
        $project->delete();

        return back()->with('success', 'Le projet a été retiré du portfolio.');
    }
}
