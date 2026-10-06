# Passerelle

Plateforme qui relie les étudiants, les établissements et les entreprises autour des stages, de l’alternance et de la première expérience professionnelle.

## Stack

- Laravel 13 / PHP
- Blade (HTML), CSS, JavaScript
- MySQL en production et WampServer en local
- Laravel Socialite pour Google OAuth

## Démarrage local

1. Lancez Apache et MySQL dans WampServer.
2. Créez la base `passerelle` dans phpMyAdmin.
3. Réglez la base de données dans `.env` :

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=passerelle
DB_USERNAME=root
DB_PASSWORD=
```

4. Lancez les migrations :

```text
php artisan migrate
```

5. Lancez le serveur :

```text
php artisan serve
```

6. Ouvrez `http://127.0.0.1:8001`.

La procédure Google OAuth est expliquée dans `docs/google-oauth-setup.md`.

## Déploiement public

Avant une mise en ligne publique, créez les variables d’environnement chez
l’hébergeur : `APP_KEY`, `APP_URL`, la base MySQL, les identifiants e-mail et
les clés Google OAuth. Aucun de ces secrets ne doit être placé dans Git.

Les détails de préparation sont dans `docs/deployment-checklist.md`.
