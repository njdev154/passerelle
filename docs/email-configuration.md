# Envoi réel des e-mails

L’inscription, la vérification de l’adresse e-mail et la réinitialisation de mot de passe sont déjà implémentées. Pour que les liens arrivent dans une véritable boîte de réception, il faut connecter Passerelle à un fournisseur d’envoi d’e-mails transactionnels.

## Avant le déploiement

Ne jamais enregistrer de mot de passe SMTP, de clé API ou de secret dans GitHub. Ils doivent rester uniquement dans le fichier `.env` sur l’ordinateur de développement et dans les variables secrètes de l’hébergeur.

## Valeurs à renseigner

Le fournisseur d’e-mails choisi fournira les valeurs. Elles remplacent les lignes `MAIL_...` du fichier `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-fournisseur.tld
MAIL_PORT=587
MAIL_USERNAME=votre-identifiant
MAIL_PASSWORD=votre-mot-de-passe-smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="bonjour@votre-domaine.tld"
MAIL_FROM_NAME="Passerelle"
```

Ensuite, vider la configuration mise en cache :

```powershell
& 'C:\Program Files\PHP\php.exe' artisan config:clear
```

Pour le lancement public, le domaine de l’expéditeur doit être vérifié chez le fournisseur. Une adresse personnelle Gmail ne doit pas être utilisée comme expéditeur de production.
