<x-layouts.app title="Déposer une offre — Passerelle">
    <section class="auth-page">
        <div class="auth-panel wide-panel">
            <p class="eyebrow">{{ strtoupper($company->name) }}</p>
            <h1>Déposer une offre.</h1>
            <p class="muted">Votre offre sera contrôlée avant d’être visible dans le catalogue public.</p>
            <form method="post" action="{{ route('company.offers.store') }}" class="form-grid">
                @csrf
                <label class="form-wide">Intitulé du poste<input name="title" value="{{ old('title') }}" required placeholder="Ex. Stagiaire développeur web"></label>
                <label>Type de contrat<select name="contract_type" required><option value="">Choisir</option><option value="internship" @selected(old('contract_type') === 'internship')>Stage</option><option value="apprenticeship" @selected(old('contract_type') === 'apprenticeship')>Alternance</option><option value="first_job" @selected(old('contract_type') === 'first_job')>Premier emploi</option></select></label>
                <label>Ville<input name="city" value="{{ old('city', $company->city) }}" required></label>
                <label>Modalité<select name="work_mode" required><option value="on_site">Sur site</option><option value="hybrid" @selected(old('work_mode') === 'hybrid')>Hybride</option><option value="remote" @selected(old('work_mode') === 'remote')>À distance</option></select></label>
                <label>Niveau souhaité <small>facultatif</small><input name="required_level" value="{{ old('required_level') }}" placeholder="Ex. Bac+2 à Bac+5"></label>
                <label class="form-wide">Description de la mission<textarea name="description" rows="8" required minlength="80" maxlength="8000" placeholder="Décrivez les missions, les compétences attendues et ce que l’étudiant pourra apprendre.">{{ old('description') }}</textarea></label>
                <label>Date limite <small>facultatif</small><input type="date" name="application_deadline" min="{{ now()->toDateString() }}" value="{{ old('application_deadline') }}"></label>
                @if ($errors->any())<p class="field-error form-wide">Veuillez corriger les informations indiquées avant d’envoyer l’offre.</p>@endif
                <button class="button button-primary form-wide" type="submit">Envoyer pour vérification</button>
            </form>
        </div>
    </section>
</x-layouts.app>
