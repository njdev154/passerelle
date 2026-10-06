<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function companies(): View
    {
        return view('public.companies');
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function advice(): View
    {
        return view('public.advice');
    }

    public function faq(): View
    {
        return view('public.faq');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Votre message a bien été reçu. Notre équipe vous répondra dès que possible.');
    }

    public function privacy(): View
    {
        return view('public.privacy');
    }

    public function terms(): View
    {
        return view('public.terms');
    }
}
