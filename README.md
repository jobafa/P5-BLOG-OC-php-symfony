# P5-BLOG-OC-php-symfony
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/07ac88bedcaf4d7aad92ec4eb8752733)](https://www.codacy.com/gh/jobafa/P5-BLOG-OC-php-symfony/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=jobafa/P5-BLOG-OC-php-symfony&amp;utm_campaign=Badge_Grade)

Formation OpenClassrooms 
Parcours : "Développeur d'applications PHP/Symfony"


Intructions d'installation du projet :

1 : Copier les fichiers du projet dans votre serveur web via la commande :
git clone https://github.com/jobafa/P5-BLOG-OC-php-symfony chemin/vers/votre-dossier

2 : Créer une base données sur votre SGDB  et importer le fichier :
diagrammes/OCP5BLOG.sql pour créer les tables du blog.

3 : Dans le fichier config/config.php :

- Ajouter l'adresse de contact : define('CF_EMAIL', 'votre email');

- Definissez les paramètres de connexion la base de données :
// DEV
define('DB_HOST', 'localhost');
define('DB_NAME', '');// Nom de la base de données
define('DB_USER', '');// Nom d'utilisateur
define('DB_PASS', '');// Mot de passe

- Modifier les lignes 14 et 21 en y mettant respectivement le nom de votre serveur et l'Url du site.

Vous pouvez lancer le site maintenant

- Inscription :

cliquez sur s'inscrire dans la barre de Navigation en haut.
créez votre compte, activez le via le mail reçu (c'est un compte utilisateur ).

- Passer en Compte Admin :

Aller dans la base de données, dans la table "user", dans votre enregistrement, remplacer la valeur de la colonne "usertype_id" par la valeur 1.
Enregistrez la modification.

Vous avez maintenant un compte Admin qui vous donne l'accès total
au module d'administration du blog.

Bonne navigation !
