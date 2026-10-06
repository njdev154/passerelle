<x-layouts.app title="Créer un compte — Passerelle">
    <section class="auth-page">
        <div class="auth-panel">
            <p class="eyebrow">CRÉER UN COMPTE</p>
            <h1>Faisons connaissance.</h1>
            <p class="muted">Votre espace est personnel. Vous choisirez ensuite votre rôle sur Passerelle.</p>
            <a class="google-button" href="{{ route('auth.google.redirect') }}"><img src="{{ asset('images/google-g.png') }}" alt="" width="20" height="20">Continuer avec Google</a>
            <div class="or"><span>ou</span></div>
            <form method="post" action="{{ route('register.store') }}">
                @csrf
                <div class="form-grid compact-grid"><label>Prénom<input name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" required></label><label>Nom<input name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" required></label></div>
                @error('first_name')<p class="field-error">{{ $message }}</p>@enderror @error('last_name')<p class="field-error">{{ $message }}</p>@enderror
                <label>Adresse e-mail<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></label>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
                <label>Mot de passe<input name="password" type="password" autocomplete="new-password" required aria-describedby="password-help"></label>
                <p id="password-help" class="helper">10 caractères minimum, avec majuscule et chiffre.</p>
                <label>Confirmer le mot de passe<input name="password_confirmation" type="password" autocomplete="new-password" required></label>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
                <button class="button button-primary button-block" type="submit">Créer mon compte</button>
            </form>
            <p class="helper">Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </section>
</x-layouts.app>
