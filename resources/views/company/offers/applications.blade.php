<x-layouts.app :title="'Candidatures — '.$offer->title">
    <section class="profile-page container">
        <div class="profile-heading"><div><p class="eyebrow">CANDIDATURES</p><h1>{{ $offer->title }}</h1><p class="muted">{{ $offer->company->name }} · {{ $offer->city }} · {{ $applications->count() }} candidature{{ $applications->count() > 1 ? 's' : '' }}</p></div><a class="back-link" href="{{ route('company.dashboard') }}">← Mes offres</a></div>
        @if(session('success'))<p class="success-message">{{ session('success') }}</p>@endif
        @forelse($applications as $application)
            <article class="candidate-card"><div class="candidate-top"><div><p class="offer-meta">{{ $application->profile->level_of_study }} · {{ $application->profile->field_of_study }} · {{ $application->profile->city }}</p><h2>{{ $application->profile->user->first_name }} {{ $application->profile->user->last_name }}</h2><p>{{ $application->profile->headline ?: 'Profil étudiant Passerelle' }}</p></div><form method="post" action="{{ route('company.applications.update', $application) }}" class="status-form">@csrf @method('PATCH')<label class="sr-only" for="status-{{ $application->id }}">Statut</label><select id="status-{{ $application->id }}" name="status"><option value="reviewed" @selected($application->status === 'reviewed')>Consultée</option><option value="shortlisted" @selected($application->status === 'shortlisted')>Présélectionnée</option><option value="interview" @selected($application->status === 'interview')>Entretien</option><option value="accepted" @selected($application->status === 'accepted')>Acceptée</option><option value="rejected" @selected($application->status === 'rejected')>Non retenue</option></select><button class="button button-secondary" type="submit">Mettre à jour</button></form></div>
                @if($application->cover_letter)<div class="candidate-section"><h3>Message</h3><p>{{ $application->cover_letter }}</p></div>@endif
                @if($application->profile->biography)<div class="candidate-section"><h3>À propos</h3><p>{{ $application->profile->biography }}</p></div>@endif
                <div class="candidate-section"><h3>Projets visibles</h3><div class="candidate-projects">@forelse($application->profile->projects->where('visibility', true) as $project)<article><strong>{{ $project->title }}</strong><p>{{ $project->description }}</p>@if($project->project_url)<a href="{{ $project->project_url }}" target="_blank" rel="noopener">Voir le projet</a>@endif</article>@empty<p class="muted">Aucun projet public ajouté.</p>@endforelse</div></div>
            </article>
        @empty
            <div class="empty-offers"><strong>Aucune candidature reçue.</strong><p>Les candidatures des étudiants apparaîtront ici dès qu’elles seront envoyées.</p></div>
        @endforelse
    </section>
</x-layouts.app>
