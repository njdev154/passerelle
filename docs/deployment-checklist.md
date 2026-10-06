# Préparer Passerelle pour la mise en ligne

Cette application ne peut pas être hébergée sur GitHub Pages : elle nécessite
PHP, Laravel et une base de données MySQL. GitHub sert uniquement à conserver
le code source.

## Avant de créer le dépôt public

- Vérifier que `.env` reste ignoré par Git.
- Ne jamais ajouter `bootstrap/cache/*.php`, les journaux ou les fichiers de
  stockage au dépôt.
- Laisser les secrets Google OAuth, SMTP et `APP_KEY` uniquement dans les
  variables sécurisées de l'hébergeur.

## Variables à configurer chez l'hébergeur

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://votre-domaine.tld`
- Une clé `APP_KEY` générée pour la production
- Les paramètres MySQL fournis par l'hébergeur
- Un fournisseur d'e-mails transactionnels et une adresse d'expédition vérifiée
- `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` et
  `GOOGLE_REDIRECT_URI=https://votre-domaine.tld/auth/google/callback`

## Commandes de mise en service

```text
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Contrôles après publication

1. Créer un compte avec une adresse e-mail de test.
2. Vérifier la réception du message de confirmation et du lien de réinitialisation.
3. Tester Google avec l'URL de production enregistrée dans Google Cloud.
4. Vérifier les accès étudiant, entreprise, établissement et administration.
5. Mettre à jour les mentions légales avec les informations réelles de l'activité.
