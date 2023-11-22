# TODO-LIST

## À propos
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/77732f72d23c42989fe86fa6d3979354)](https://app.codacy.com/gh/Alexis3386/todo/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

Projet Openclassroom n°8 du parcour développeur d'application Symfony.
Le but de se projet est d'améliorer un projet existant de todo list.

## Table des matières

- 🪧 [À propos](#à-propos)
- 📦 [Prérequis](#prérequis)
- 🚀 [Installation](#installation)
- 🏗️ [Construit avec](#construit-avec)

## Prérequis

 - [PHP 8.2.8.](https://www.php.net/manual/fr/) 
 - [Composer 2.5.8](https://getcomposer.org/doc/)
 - [Symfony 6.3.8](https://symfony.com/doc/current/index.html)
 - [MariaDB version 11.0.2](https://mariadb.org/documentation/)
 - [git version 2.41.01](https://git-scm.com/doc)

## Installation

Pour récupérer le projet en local vous pouvez cloner le repository [todo](https://github.com/Alexis3386/todo)

```
git clone https://github.com/Alexis3386/todo
```
aller dans le dossier todo
installer les dépendances avec :
```
composer install
```
configuer la base de données dans le .env du projet

ensuite créer la base de données avec :
```
php bin/console doctrine:database:create
```
créer les migrations :

```
php bin/console make:migration
```
jouer les migrations :
```
php bin/console doctrine:migrations:migrate
```
lancer les fixture pour avoir un jeu de donnée :
```
php bin/console doctrine:fixtures:load
```

## Construit avec

### Langages & Frameworks 

-[PHP 8.2.8.](https://www.php.net/manual/fr/)
-[Symfony 6.3.8](https://symfony.com/doc/current/index.html)


### Outils

- [PhpUnit 9.6.13](https://phpunit.de/documentation.html)

#### CI

Pour lancer les tests on peut utiliser la commande :

```
.\vendor\bin\phpunit --configuration phpunit.xml.dist
```
Il faut rejouer les fixtures avant de relancer 
les tests pour avoir les bonnes données pour les tests fonctionnels