# SAND-FRAMEWORK for Modular-Applications

---
---

Cette architecture MVC Objet est composée d'un moteur de template (Blade ou Twig)

Les urls d'accès sont de type www.domain.tld/le_nom_de_mapage/varname1/varvalue1/varname2/varvalue2/ ...

Afin de créer une nouvelle page vous devez instancier trois fichiers contenant diverses variables, dont voici les adresses:

---

`application > include > controlleurs > le_nom_de_mapage.php` contenant:
 - les commandes permettant de gérer un formulaire
 - un ou plusieurs accès à la base de données
 - des variables instanciées dans le tableau `$templateData` permettant l'affichage dans la vue blade ou twig

---

`application > include > modeles > le_nom_de_mapage.model` contenant
 les variables spécifiques à la page de l'application. soit par exemple:
```
name : le_nom_de_mapage
page_title : le title du head de la page html rendue
description : ma description pour les moteurs de recherche
engine : none
authentification : no
ariane : {acceuil, test d'acceuil}
arianelink : {index, le_nom_de_mapage}
paramsN : paramètre(s) supplémentaire(s)
```
avec en plus de cela : 
`engine : blade` pour un layout blade ou `engine : twig` pour un layout twig

---

`application > include > vues > view > le_nom_de_mapage.blade.php` contenant le layout `blade` a instancier
`application > include > vues > view > le_nom_de_mapage.html.twig` contenant le layout `twig` a instancier

> Vous pouvez aussi tout à fait utiliser la commande:
> 
> `php console/bin.php page:add`

Pour les modules, c'est un peu plus compliqué : il faut instancier ces trois précédents fichiers en faisant appel la class Modular,
ne pas oublier de référencer le module dans le dossier modules > setup > registre.model, ajouter le dossier contenant le code du module
et faire correspondre le nom du controlleur frontal du module avec le registre.
