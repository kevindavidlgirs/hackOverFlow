# HackOverFlow
Est un projet de réseau social qui permet de créer un compte, de poser des questions, ajouter des commentaires, voter,... [Screenshot](https://imgur.com/a/WrnDLCF)

### Conditions préalables
Vous avez besoin pour l'utilisation de ce logiciel de "xampp" et d'un navigateur.

```
Proposition :

xampp -> https://www.apachefriends.org/fr/download.html
firefox -> https://www.mozilla.org/fr/firefox/new/

```

### Installation
1) Téléchargez et installez xampp
2) Lancez le "panel control"
3) Une fois lancé démarrez "Apache" et "MySql"
4) Lancez votre navigateur et entrez cette URL -> http://localhost/phpmyadmin/
5) Accédez aux "compte utilisateurs" dans "PhpMyAdmin" et changez le code de l'utilisateur "root" qui a pour nom d'hôte "localhost"
6) Changez ensuite son mot de passe
7) Accédez ensuite au fichier "config.inc.php" dans le dossier xampp se trouvant normalement ici "C:\xampp\phpMyAdmin\config.inc.php"
8) A cette ligne -> $cfg['Servers'][$i]['auth_type'] = 'config'; changez 'config' par 'cookie'
9) Reconnectez-vous à http://localhost/phpmyadmin/ et vérifier qu'il y a maintenant une page de connexion et que votre mot de passe fonctionne!
10) Placez le dossier du projet dans "xampp" dans le dossier "htdocs" -> "C:\xampp\htdocs"
11) Dans le dossier "config" du projet, éditez le fichier "dev.ini" et ajoutez votre mot de passe précédemment créé à côté de "dbpassword = ". Exemple : dbpassword = root
12) Retournez dans votre navigateur et entrez cette URL -> "http://localhost/prwb_1920_G09/". Vous devriez voir ces messages dans votre navigateur :

 Importation des données en cours...
 La base de données a été correctement créée
 Les données correctement importées
 Retour à l'index

13) Retournez dans le dossier du projet, entrez dans le dossier "config" et reéditer le dossier "dev.ini", changez simplement "default_controller = setup" par "default_controller = post"
14) Enjoy ! :)


## Utilisation
1) Dans un premier temps vous pouvez :
- Créer un utilisateur
- Poser une question, répondre à des questions, ajouter des commentaire, voter et observer les statistiques des différents utilisateurs.

2) Dans un seconde temps vous pouvez :
- Vous connectez avec le compte de l'administrateur (code d'accès ci-dessous) et observer les fonctionnalités dont il a accès (ajout de nouveaux tags, suppression des n'importe quelle question, ...)


### Liste des utilisateurs et mots de passes
  * Utilisateur `admin`, mot de passe `Password1,`
  * Utilisateur `user1`, mot de passe `Password1,`
  * Utilisateur `user2`, mot de passe `Password1,`
  * Utilisateur `user3`, mot de passe `Password1,`
  * Utilisateur `user4`, mot de passe `Password1,`

## Architecture
- J'ai utilisé l'architecture MVC pour ce projet.

## Auteurs
* **Kevin David L. Girs** - [kevindavidlgirs](https://github.com/kevindavidlgirs)

## Info
- Projet de groupe pour un bachelier en informatique de gestion donné à l'EPFC (Bruxelles-capitale) https://www.epfc.eu/
- La base de données ainsi que le framework ont été créés par les professeurs de l'EPFC 




