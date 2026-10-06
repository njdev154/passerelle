# Activer la connexion Google de Passerelle

## Ce qui est déjà prêt dans le projet

- Laravel Socialite est installé.
- Les routes sont en place :
  - `/auth/google/redirect`
  - `/auth/google/callback`
- Lors de la première connexion, Passerelle crée le compte, récupère le nom, l’e-mail et l’avatar fournis par Google, puis demande le rôle de l’utilisateur.
- Les secrets restent uniquement dans le fichier `.env`, qui ne doit jamais être envoyé sur GitHub.

## Configuration dans Google Cloud

1. Connectez-vous à Google Cloud Console avec le compte qui possédera le projet.
2. Créez un projet nommé `Passerelle`.
3. Configurez l’écran de consentement OAuth avec le nom, l’e-mail de support et les informations de confidentialité de la plateforme.
4. Créez un identifiant OAuth de type **Application Web**.
5. Ajoutez l’URI de redirection locale :

```text
http://127.0.0.1:8001/auth/google/callback
```

6. Copiez l’identifiant client et la clé secrète dans `.env` :

```dotenv
GOOGLE_CLIENT_ID="votre-identifiant-client"
GOOGLE_CLIENT_SECRET="votre-cle-secrete"
GOOGLE_REDIRECT_URI="http://127.0.0.1:8001/auth/google/callback"
```

7. Après chaque modification de `.env`, exécutez :

```text
php artisan config:clear
```

## Avant publication

Ajoutez aussi l’URL du domaine public, par exemple :

```text
https://passerelle.ci/auth/google/callback
```

Google demandera une URL de confidentialité publique et les informations de marque de Passerelle avant de sortir du mode de test.
