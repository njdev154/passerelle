<x-layouts.app title="Mon espace — Passerelle">
    <section class="dashboard-page container">
        <p class="eyebrow">TABLEAU DE BORD</p>
        <h1>Bonjour, {{ $user->first_name }}.</h1>
        <p class="muted">Votre espace {{ match($user->role) { 'student' => 'étudiant', 'company' => 'entreprise', 'school' => 'établissement', default => 'personnel' }} est prêt.</p>
        <div class="empty-state">
            <h2>La prochaine étape arrive.</h2>
            <p>Nous venons de mettre en place le socle sécurisé : compte, rôle, redirection et future connexion Google. Les fonctionnalités de votre tableau de bord seront construites juste après.</p>
        </div>
    </section>
</x-layouts.app>
