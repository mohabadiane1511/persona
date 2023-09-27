# Projet Symfony - Gestion de Personnes

Ce projet Symfony permet de gérer des personnes (nom, prénom, date de naissance) via une API REST.

## Configuration Requise

- PHP 8.1 ou supérieur
- Composer (pour gérer les dépendances)
- Symfony CLI (pour exécuter l'application)

## Installation

1. Clonez ce dépôt Git :

   ```shell
   git clone https://github.com/votre-utilisateur/projet-symfony-personnes.git

   ```

2. Accédez au répertoire du projet :

   ```bash
   cd <project-directory
   ```

3. Installez les dépendances avec Composer :

   ```bash
   composer install
   ``

   ```

4. Creez la base de donnees et faites les migrations

```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
```

5.  Démarrez le serveur Symfony :

```bash
symfony server:start
```

## ENDPOINTS

accedez a la documentaion de postman pour visulaiser les endpoints

```bash
https://documenter.getpostman.com/view/29518318/2s9YJZ345v
```
