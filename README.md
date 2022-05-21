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
    
## Lancement du site en local

    symfony server:start # Démarre le serveur local PHP
    symfony open:local # Ouvre la page d'acceuil du site
    yarn dev-server # Lance la compilation en direct des fichiers JS et SCSS de Webpack
    
Laissez la console ouverte pour continuer à naviguer sur le site. Vous pouvez à tout moment éteindre le server PHP avec la commande ’symfony server:stop’.

## Configuration

TODO

## Bonus

Créer un administrateur dans la base de donnée pour pouvoir se connecter à l'installation du site :

    bin/console app:create-admin
  
