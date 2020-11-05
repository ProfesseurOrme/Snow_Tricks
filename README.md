# Projet 6-SnowTricks

Conception d'un site communautaire sur le Snowboard pour le besoin d'un projet pédagogique.

Les diagrammes UML demandés `UML_Diagrammes.zip` se trouvent à la racine du projet.

## Environnement utilisé durant le développement
* [Symfony 5.1.5](https://symfony.com/doc/current/setup.html) 
* [Composer 1.8.6](https://getcomposer.org/doc/00-intro.md) 
* Bootstrap ([Material Kit 2.0.7](https://demos.creative-tim.com/material-kit/docs/2.1/getting-started/introduction.html))
* MAMP 6 (985)
    * Apache 2.4.46
    * PHP 7.3.11
    * MySQL 5.7.30
* Yarn 1.22.4
* Node.js 12.14.1

## Installation
1- Clonez le repository GitHub dans le dossier voulu :
```
    git clone https://github.com/ProfesseurOrme/Snow_Tricks.git
```

2- Placez vous dans le répertoire de votre projet et installez les dépendances du projet avec la commande de [Composer](https://getcomposer.org/doc/00-intro.md) :
```
    composer install
```

3- Configurez vos variables d'environnement dans le fichier `.env` tel que :

* La connexion à la base de données  :
```
    DATABASE_URL=mysql://db.username:db.password@127.0.0.1:3306/snow_tricks
```

* Renseignez votre provider pour l'envoi de mail (ici GMAIL) : 
```
    MAILER_DSN=gmail+smtp://mail.emailaddress:mail.password@default
```

* Si vous utilisez votre propre SMTP :
```
    MAILER_DSN=smtp://user:pass@smtp.example.com:port
```

* Ou si vous utiliser un SMTP tier comme celui utilisé dans le projet (Gmail), veuillez vous référer à cette
 [documentation](https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport).

4- votre adresse mail d'envoi dans le fichier `.env` : 
```
    MAIL_ADMIN=your.address@mail.com
```

5- Téléchargez et installez les dépendances front-end du projet avec : 

* [Npm](https://www.npmjs.com/get-npm)  :
```
    npm install
```

* ou [Yarn](https://yarnpkg.com/getting-started/install)  :
```
    yarn install
```

6- Créer un build d'assets (grâce à Webpack Encore) : 

* avec [Npm](https://www.npmjs.com/get-npm) :
```
    npm run build
```
* ou [Yarn](https://yarnpkg.com/getting-started/install)  :
```
    yarn encore production
```

7- Si le fichier `.env` est correctement configuré, créez la base de données avec la commande ci-dessous :
```
    php bin/console doctrine:database:create
```
8- Créez les différentes tables de la base de données :
```
    php bin/console doctrine:migrations:migrate
```
9- Installer des données fictives avec des fixtures pour agrémenter le site :
```
    php bin/console doctrine:fixtures:load
```
10- Votre projet est prêt à l'utilisation ! Pour utiliser l'application dans un environnement local, veuillez vous
 renseigner sur cette
 [documentation](https://symfony.com/doc/current/setup.html#running-symfony-applications).