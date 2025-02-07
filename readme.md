# URL DU SITE 
https://jeremytahar.fr/ent

# Identifiants de test 

## Élève
- Login : jeremy.tahar
- Mot de passe : mdp


# Installation du site sur un serveur local

Voici toutes les étapes pour installer le site sur un autre serveur.

## Étape 1: Installation 
- Télécharger le dossier zip du site 
- Décompresser le dossier zip dans un dossier ent dans le dossier web de votre serveur (exemple C:\wamp64\www pour WAMP)

## Étape 2: Importation base de données 
- Créer une base de données dans votre gestionnaire de base de données
- Importer le fichier SQL dans la base de données (fichier SQL se trouve dans le dossier zippé)

## Étape 3: Configuration fichier de connexion à la BDD
- Ouvrir le fichier de configuration (fichier app/config/dbconnect.php) dans le dossier ent
- Remplacer les informations de connexion par celles de votre base de données:
    <?php
        $db = new PDO('mysql:host=localhost;dbname=ent', 'utilisateur', 'mot_de_passe');
    ?>
    - host : Adresse du serveur MySQL (généralement localhost en local).
    - dbname : Nom de la base de données (ent dans cet exemple).
    - utilisateur : Nom d'utilisateur MySQL.
    - mot_de_passe : Mot de passe MySQL.

## Étape 4: Lancer le serveur et tester
- Lancer votre serveur local (WAMP, XAMPP, etc.)
- Ouvrir votre navigateur et aller sur http://localhost/ent (remplacer ent par le nom de votre dossier où se trouve le site)
- Tester les fonctionnalités du site.
