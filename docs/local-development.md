# Développement local

Passerelle utilise le port `8001` en local afin de ne pas entrer en conflit avec un autre projet Laravel sur le port `8000`.

Ouvrir : `http://127.0.0.1:8001`

Lancer l’application :

```powershell
& 'C:\Program Files\PHP\php.exe' artisan serve --host=127.0.0.1 --port=8001
```

La base de données locale est `passerelle` dans MySQL Wamp.

Pour tester Google en local, l’URI de redirection à enregistrer dans Google Cloud est :

`http://127.0.0.1:8001/auth/google/callback`
