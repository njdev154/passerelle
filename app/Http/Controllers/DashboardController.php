<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->user()->role) {
            return redirect()->route('onboarding.role');
        }

        if ($request->user()->role === 'company' && ! $request->user()->companies()->exists()) {
            return redirect()->route('company.create');
        }

        if ($request->user()->role === 'school' && ! $request->user()->schools()->exists()) {
            return redirect()->route('school.create');
        }

        $user = $request->user();

        if ($user->role === 'student') {
            $profile = $user->studentProfile;
            $applications = $profile?->applications()->with('offer.company')->latest('submitted_at')->get() ?? collect();

            return view('student.dashboard', compact('user', 'profile', 'applications'));
        }

        return view('dashboard', compact('user'));
    }

    public function role(): View
    {
        return view('auth.choose-role');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:student,company,school'],
        ]);

        $request->user()->update(['role' => $data['role']]);

        return match ($data['role']) {
            'company' => redirect()->route('company.create'),
            'school' => redirect()->route('school.create'),
            default => redirect()->route('dashboard'),
        };
    }
}
