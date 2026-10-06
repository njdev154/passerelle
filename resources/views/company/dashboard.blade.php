<x-layouts.app title="Espace entreprise — Passerelle">
    <section class="dashboard-page container company-dashboard">
        <p class="eyebrow">ESPACE ENTREPRISE</p>
        <div class="dashboard-heading"><div><h1>{{ $company->name }}</h1><p class="muted">{{ $company->sector }} · {{ $company->city }}</p></div><a class="button button-primary" href="{{ route('company.offers.create') }}">Déposer une offre</a></div>
        @if(session('success'))<p class="success-message">{{ session('success') }}</p>@endif
        <section class="management-section"><div class="section-top"><div><p class="eyebrow">VOS OFFRES</p><h2>{{ $company->offers_count }} offre{{ $company->offers_count > 1 ? 's' : '' }}</h2></div></div>
            @forelse($offers as $offer)
                <article class="offer-row"><div><p class="offer-meta">{{ $offer->contractLabel() }} · {{ $offer->city }}</p><h2>{{ $offer->title }}</h2><p>Statut : <strong>{{ $offer->status === 'pending_review' ? 'En vérification' : ($offer->status === 'published' ? 'Publiée' : ($offer->status === 'draft' ? 'Brouillon' : 'Archivée')) }}</strong> · {{ $offer->applications_count }} candidature{{ $offer->applications_count > 1 ? 's' : '' }}</p></div><a class="text-link" href="{{ route('company.offers.applications', $offer) }}">Voir les candidatures →</a></article>
            @empty
                <div class="empty-offers"><strong>Vous n’avez pas encore déposé d’offre.</strong><p>Chaque offre est contrôlée avant de devenir visible par les étudiants.</p></div>
            @endforelse
        </section>
    </section>
</x-layouts.app>
