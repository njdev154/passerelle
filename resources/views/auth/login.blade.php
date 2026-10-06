<x-layouts.app title="Connexion — Passerelle">
    <section class="auth-page">
        <div class="auth-panel">
            <p class="eyebrow">CONNEXION SÉCURISÉE</p>
            <h1>Content de vous revoir.</h1>
            <p class="muted">Accédez à vos candidatures, vos offres et vos documents depuis un seul espace.</p>
            @if ($errors->has('google'))
                <p class="notice" role="alert">{{ $errors->first('google') }}</p>
            @endif
            @if (session('success'))<p class="success-message" role="status">{{ session('success') }}</p>@endif
            <a class="google-button" href="{{ route('auth.google.redirect') }}"><img src="{{ asset('images/google-g.png') }}" alt="" width="20" height="20">Continuer avec Google</a>
            <div class="or"><span>ou</span></div>
            <form method="post" action="{{ route('login.store') }}">
                @csrf
                <label for="email">Adresse e-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="vous@exemple.ci" required autofocus>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Votre mot de passe" required>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
                <label class="check-row" for="remember"><input id="remember" name="remember" type="checkbox" value="1"> <span>Rester connecté</span></label>
                <button class="button button-primary button-block" type="submit">Se connecter</button>
            </form>
            <div class="auth-links"><a href="{{ route('password.request') }}">Mot de passe oublié ?</a><p>Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p></div>
        </div>
    </section>
</x-layouts.app>
