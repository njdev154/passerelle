<x-layouts.app title="Choisir votre rôle — Passerelle">
    <section class="auth-page">
        <div class="auth-panel wide-panel">
            <p class="eyebrow">PREMIÈRE CONNEXION</p>
            <h1>Quel espace vous correspond&nbsp;?</h1>
            <p class="muted">Vous pourrez compléter votre profil juste après. Ce choix permet de vous ouvrir les bons outils.</p>
            <form method="post" action="{{ route('onboarding.role.store') }}" class="role-grid">
                @csrf
                <button name="role" value="student" type="submit"><b>Étudiant</b><span>Créer mon profil, candidater et suivre mon expérience.</span></button>
                <button name="role" value="company" type="submit"><b>Entreprise</b><span>Publier une offre et accompagner mes recrutements.</span></button>
                <button name="role" value="school" type="submit"><b>Établissement</b><span>Suivre les parcours de mes étudiants.</span></button>
            </form>
        </div>
    </section>
</x-layouts.app>
