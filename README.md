# Planeur-Bailleau

Hey ! Tu comprends rien à la documentation qui suit ? C'est normal, c'est un métier. Mais n'hésite pas à venir me voir pour que je t'explique les bases et que l'on fasse en sorte que, toi aussi, tu puisses contribuer ...

Thibault HENRY
thibault@henry.pro
+33 6 72 39 17 59
Le dernier mobile tout en bas du camping (et il y a toujours de la bière au frais)

## Requirements

    php >= 7.4
    symfony-cli
    composer
    PostgreSQL
    Yarn
  
## Installation locale

    git clone git@github.com:Tilotiti/Planeur-Bailleau.git # Téléchargement du code source
    cd Planeur-Bailleau # Création du dossier du projet
    cp .env .env.local # Duplication des paramètres par défaut
  
Modifiez le fichier ’.env.local’ pour configurer le projet.
  
    composer install # Installation des dépendances PHP
    yarn install # Installion des dépendances JS
    symfony server:ca:install # Installation des certificats de développement
    bin/release # Suppression du cache et mise à jour du schéma de la BDD
 
## Mise à jour du projet local

    git pull
    bin/release
    
Si une erreur survient, vérifiez que votre fichier ’.env.local’ est à jour par rapport au fichier ’.env’ et relancez ’bin/release’.
## Configuration

Une fois le site internet installé en local, il faut maintenant le configuré pour qu'il fonctionne correctement. Pour cela, c'est assez simple. Il suffit de remplir le fichier `.env.local` qui est propre à ton ordinateur. Tu peux te baser sur les paramètres suivants :

- **APP_SECRET** : Mets y une chaîne de caractère aléatoire. C'est ce qui va permettre à l'application de chiffrer automatiquement les cookies, les mots de passes, ainsi que les formulaires que l'on poste.
- **MAILER_DSN** : C'est l'URL de connexion au serveur d'envoi d'e-mail en imap ... Tu ne sais pas ce que c'est et tu ne comptes pas tester l'envoi d'e-mail depuis le site internet ? Tu t'en fou ...
- **DATABASE_URL** : C'est le seul truc vraiment important. C'est l'URL de connexion à ta base de données locale. Si tu as bien installé PostgreSQL sur ton ordinateur, ça devrait être du style : `postgresql://[ton nom d'utilisateur]:[ton mot de passe]@127.0.0.1:5432/planeur-bailleau?serverVersion=11&charset=utf8`.
- **AWS_S3_*** : Ce sont les variables qui te permettent de connecter le site internet à un "bucket AWS S3". Ca sert à pouvoir charger des fichiers externes sur le site internet depuis des formulaires. Je peux t'en fournir pour tester si besoin.- **GOOGLE_RECAPTCHA*** : Ce sont les identifiants Google qui permettent au site internet d'avoir un Captcha et d'éviter que Martine reçoive du spam russe 5 fois par jours pour qu'on achète un ASH25 en bitcoin.


## Lancement du site en local

    bin/server

Deux serveurs webs sont lancés automatiquement : le serveur backend et le serveur frontend. Le site est automatiquement ouvert à la fin de la compilation du projet. La moindre modification est prise en compte automatiquement.

## Bonus

Créer un administrateur dans la base de donnée pour pouvoir se connecter à l'installation du site :

    bin/console app:create-admin
  
## Un commentaire ?

Je ne suis pas infaillible. Loin de là. Donc si tu penses qu'il manque quoi que ce soit à la documentation, n'hésite pas à me contacter : thibault@henry.pro, +33 6 72 39 17 59, et je serais ravi de te filer un coup de main et de compléter cette courte documentation pour t'aider à contribuer à notre club.