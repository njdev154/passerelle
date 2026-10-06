<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Passerelle relie les étudiants, les écoles et les entreprises autour des premières expériences professionnelles.">
        <title>{{ $title ?? 'Passerelle' }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/passerelle-symbol.svg') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/passerelle.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    </head>
    <body>
        <a class="skip-link" href="#contenu">Aller au contenu</a>
        <header class="header">
            <div class="container nav">
                <a class="brand" href="{{ route('home') }}" aria-label="Passerelle, accueil"><img src="{{ asset('images/passerelle-symbol.svg') }}" alt="" width="34" height="34"><span>passerelle</span></a>
                <nav aria-label="Navigation principale">
                    <a href="{{ route('offers.index') }}">Offres</a><a href="{{ route('public.companies') }}">Entreprises</a><a href="{{ route('public.advice') }}">Conseils</a><a href="{{ route('public.about') }}">À propos</a>@auth @if(auth()->user()->role === 'admin')<a href="{{ route('admin.dashboard') }}">Administration</a>@endif @endauth
                </nav>
                @auth
                    <div class="nav-account"><a class="button button-secondary" href="{{ route('dashboard') }}">Mon espace</a><form method="post" action="{{ route('logout') }}">@csrf<button class="nav-logout" type="submit">Déconnexion</button></form></div>
                @else
                    <a class="button button-secondary" href="{{ route('login') }}">Se connecter</a>
                @endauth
            </div>
        </header>
        <main id="contenu">{{ $slot }}</main>
        <footer class="site-footer"><div class="container footer-grid"><div><a class="brand" href="{{ route('home') }}"><img src="{{ asset('images/passerelle-symbol.svg') }}" alt="" width="30" height="30"><span>passerelle</span></a><p>Les premières expériences professionnelles méritent un cadre clair, humain et vérifié.</p></div><div><strong>Découvrir</strong><a href="{{ route('offers.index') }}">Offres</a><a href="{{ route('public.companies') }}">Pour les entreprises</a><a href="{{ route('public.advice') }}">Conseils</a></div><div><strong>À propos</strong><a href="{{ route('public.about') }}">Notre démarche</a><a href="{{ route('public.faq') }}">Questions fréquentes</a><a href="{{ route('public.contact') }}">Contact</a><a href="{{ route('public.privacy') }}">Confidentialité</a><a href="{{ route('public.terms') }}">Conditions d’utilisation</a></div></div><div class="container footer-bottom">© {{ now()->year }} Passerelle. Tous droits réservés.</div></footer>
    </body>
</html>
