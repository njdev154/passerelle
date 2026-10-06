<x-layouts.app title="Votre organisation — Passerelle">
    <section class="auth-page">
        <div class="auth-panel wide-panel">
            <p class="eyebrow">ESPACE ENTREPRISE</p>
            <h1>Présentez votre organisation.</h1>
            <p class="muted">Ces informations permettront de vérifier votre organisation avant toute publication d’offre.</p>
            <form method="post" action="{{ route('company.store') }}" class="form-grid">
                @csrf
                <label>Nom de l’organisation<input name="name" value="{{ old('name') }}" required autocomplete="organization"></label>
                <label>Secteur d’activité<input name="sector" value="{{ old('sector') }}" required placeholder="Ex. Services numériques"></label>
                <label>Ville<input name="city" value="{{ old('city') }}" required placeholder="Ex. Abidjan"></label>
                <label>Site web <small>facultatif</small><input name="website" value="{{ old('website') }}" type="url" placeholder="https://…"></label>
                <label class="form-wide">Présentation <small>facultatif</small><textarea name="description" rows="5" maxlength="2000" placeholder="En quelques phrases, expliquez votre activité.">{{ old('description') }}</textarea></label>
                @if ($errors->any())<p class="field-error form-wide">Veuillez corriger les informations indiquées.</p>@endif
                <button class="button button-primary form-wide" type="submit">Créer l’organisation</button>
            </form>
        </div>
    </section>
</x-layouts.app>
