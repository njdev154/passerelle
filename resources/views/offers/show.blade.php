<x-layouts.app :title="$offer->title.' — Passerelle'">
    <section class="offer-detail container">
        <a class="back-link" href="{{ route('offers.index') }}">← Toutes les offres</a>
        <p class="eyebrow">{{ strtoupper($offer->contractLabel()) }} · {{ $offer->city }}</p>
        <h1>{{ $offer->title }}</h1>
        <p class="company-name">{{ $offer->company->name }} · {{ $offer->company->sector }}</p>
        <div class="detail-grid"><article><h2>La mission</h2><p>{!! nl2br(e($offer->description)) !!}</p></article><aside><h2>En bref</h2><dl><dt>Lieu</dt><dd>{{ $offer->city }}</dd><dt>Modalité</dt><dd>{{ $offer->workModeLabel() }}</dd>@if($offer->required_level)<dt>Niveau</dt><dd>{{ $offer->required_level }}</dd>@endif @if($offer->application_deadline)<dt>Clôture</dt><dd>{{ $offer->application_deadline->translatedFormat('d F Y') }}</dd>@endif</dl>@guest<a class="button button-primary button-block" href="{{ route('login') }}">Se connecter pour postuler</a>@else @if(auth()->user()->role === 'student') @if($hasApplied)<p class="application-done">Candidature envoyée</p>@else<form method="post" action="{{ route('applications.store', $offer) }}">@csrf<label for="cover_letter">Votre message <small>facultatif</small></label><textarea id="cover_letter" name="cover_letter" rows="5" maxlength="3000" placeholder="Expliquez pourquoi cette opportunité vous intéresse."></textarea>@error('application')<p class="field-error">{{ $message }}</p>@enderror<button class="button button-primary button-block" type="submit">Envoyer ma candidature</button></form>@endif @else<p class="muted">La candidature est réservée aux comptes étudiants.</p>@endif @endguest</aside></div>
    </section>
</x-layouts.app>
