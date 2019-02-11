# Ayt-place

Aty'place est un symfony  4.2.

## Installation 
- `compose install`

## Lancement en local du docker-compose

- `docker-compose up` | `docker-compose up --build`

## Créer la base de donée + table + fixture

#### Dans un premier temps se connecter au container php avec la commande `docker-compose exec php bash`

- créer la bdd`docker-compose php bin/console d:d:c`
- fait les migration (création des tables) `docker-compose php bin/console d:s:u -f`
- lance les fixtures `docker-compose php bin/console d:f:l`
- si la bdd exite déja lancer en premier `docker-compose d:d:d --force`

## lancer yarn 

- `npm install`
-  pour le dev `yarn encore dev  --watch`
-  pour la prod `yarn encore prod `
