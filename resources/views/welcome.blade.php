<x-layouts.app title="Passerelle — Études, expérience, avenir">
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <p class="eyebrow">STAGES · ALTERNANCE · PREMIER EMPLOI</p>
                <h1>Le bon départ mérite une <em>vraie passerelle.</em></h1>
                <p class="intro">Des opportunités vérifiées, un portfolio qui montre ce que vous savez faire et un suivi clair entre étudiant, entreprise et établissement.</p>
                <form class="search-card" action="{{ route('offers.index') }}" method="get">
                    <label><span class="sr-only">Métier ou compétence</span><input name="q" placeholder="Métier, compétence ou mot-clé"></label>
                    <label><span class="sr-only">Ville</span><input name="city" placeholder="Abidjan et environs"></label>
                    <button class="button button-primary" type="submit">Rechercher</button>
                </form>
            </div>
            <aside class="hero-aside">
                <p class="eyebrow">À PARTIR D’UNE CANDIDATURE</p>
                <h2>Un parcours qui ne s’arrête pas à l’envoi d’un CV.</h2>
                <p>Suivez chaque étape : candidature, entretien, convention, objectifs et évaluation finale.</p>
            </aside>
        </div>
    </section>
    <section class="offers-section container" id="offres">
        <div class="section-top"><div><p class="eyebrow">OPPORTUNITÉS</p><h2>Des offres pensées pour un premier pas.</h2></div><a href="{{ route('offers.index') }}">Voir toutes les offres →</a></div>
        @if ($offers->isNotEmpty())
            <div class="offer-grid">
                @foreach ($offers as $offer)
                    <article><p>{{ $offer->contractLabel() }} · {{ $offer->city }}</p><h3><a href="{{ route('offers.show', $offer) }}">{{ $offer->title }}</a></h3><span>{{ $offer->company->name }}</span></article>
                @endforeach
            </div>
        @else
            <div class="empty-offers"><strong>Les premières offres vérifiées arrivent bientôt.</strong><p>Les offres ne sont publiées qu’après vérification de l’entreprise. Aucune donnée de démonstration ne sera présentée comme une offre réelle.</p></div>
        @endif
    </section>
</x-layouts.app>
