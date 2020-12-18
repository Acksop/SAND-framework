# Modular Symfony Application
---
Cette architecture MVC Objet est composée d'un layout Blade (Laravel)

Les urls d'accès sont de type www.domain.tld/page/varname1/varvalue1/varname2/varvalue2/ ...

Afin de créer une nouvelle page vous devez instancier trois fichiers contenant diverses variables, dont voici les commandes:


"application > include > controlleurs > mapage.php" contenant:
> les commandes permettant de gérer un formulaire
> un ou plusieurs accès à la base de données
> les variables instanciées dans $templateData permettent l'affichage dans la vue blade

"application > include > modeles > mapage.model" contenant
>les variables spécifiques à la page de l'application exemple:
```
name : le nom de mapage
description : ma description pour les moteur de recherche
params : paramètre(s) supplémentaire(s)
```
"application > include > vues > view > mapage.blade.php contenant
> le layout blade a instancier

pour les modules symfony, c'est un peu plus compliqué il faut instancier ces trois précédents fichiers en faisant appel la class Modular,
ne pas oublier de référencer le module dans le dossier modules > setup > registre.model
et faire correspondre le nom du dossier avec le registre, ici l'exemple est syf43.


#Commandes pour  demarrer  sur la branche  dev

```
composer  update
git submodule init
git submodule update
```