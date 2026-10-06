<x-layouts.app title="Offres vérifiées — Passerelle">
    <section class="listing-hero">
        <div class="container">
            <p class="eyebrow">OPPORTUNITÉS VÉRIFIÉES</p>
            <h1>Trouvez une expérience qui compte.</h1>
            <form class="search-card" method="get" action="{{ route('offers.index') }}">
                <label><span class="sr-only">Métier ou compétence</span><input name="q" value="{{ request('q') }}" placeholder="Métier, compétence ou mot-clé"></label>
                <label><span class="sr-only">Ville</span><input name="city" value="{{ request('city') }}" placeholder="Ville"></label>
                <button class="button button-primary" type="submit">Rechercher</button>
            </form>
        </div>
    </section>
    <section class="container listing-section">
        <p class="result-count">{{ $offers->total() }} offre{{ $offers->total() > 1 ? 's' : '' }} publiée{{ $offers->total() > 1 ? 's' : '' }}</p>
        @forelse ($offers as $offer)
            <article class="offer-row">
                <div><p class="offer-meta">{{ $offer->contractLabel() }} · {{ $offer->city }} · {{ $offer->company->name }}</p><h2><a href="{{ route('offers.show', $offer) }}">{{ $offer->title }}</a></h2><p>{{ \Illuminate\Support\Str::limit($offer->description, 180) }}</p></div>
                <a class="text-link" href="{{ route('offers.show', $offer) }}">Voir l’offre →</a>
            </article>
        @empty
            <div class="empty-offers"><strong>Aucune offre ne correspond à votre recherche.</strong><p>Essayez un autre mot-clé ou revenez bientôt : chaque publication est contrôlée avant d’apparaître ici.</p></div>
        @endforelse
        @if ($offers->hasPages())<div class="pagination">{{ $offers->links() }}</div>@endif
    </section>
</x-layouts.app>
