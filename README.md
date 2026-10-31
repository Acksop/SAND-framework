# SAND-FRAMEWORK for Modular-Applications

---

## 1. 🎢 Do you want to know more ?

<br />

<details>
<summary>↗️ A quelle utilisation se destine le framework SAND ?</summary>
<p>

> Voici quelques exemples de projets pour lesquels SAND a été ou pourrait être utilisé :
>
> - Un site comprenant une documentation interne en MARKDOWN et un depot git privé (https://code-source.sand-framework.app)
> - Un outil full console pour partager les accès entre un LDAP et une grosse application de droits et de paiement de salariés
> - Une application permettant de communiquer avec un LDAP pour une gestion des droits de personnels exterieurs à une université comprenant logs et multiples droits accès
> - Une application web de newsletter avec envoi en mode console par tache CRON
> - Une application web de curriculum vitae
> - Une application web de créations de pages dynamiques
> - Une application web comprenant un wordpress, un phplist et un site reliés sur le même domaine
> - Une application comprennant un ou plusieurs module symfony avec gestion des droits d'accès par SAND
> - Une applications comprennant un ou plusieurs module laravel avec gestion des droits d'accès par SAND
> - Une application REST comprennant des appels dynamique injectés en curl 

</p>
</details>

<details>
<summary>💪 Les points forts du projet SAND-framework dans sa version actuelle</summary>
<p>

> Domain And Test(PHPUnit-Behat) Driven Development.
 
> A building metrics toujours disponible  
 
> Plusieurs modules déjà disponible (Worpress, PHPList, Gitlist, ...)
 
> Possibilité de créer des modules SAND pour un Projet SAND (plusieurs modules SAND-view-symfony pour un projet SAND-blade, par exemple)
 
> Un système d'authentification fonctionnellement testé avec des authenfication hybrides (Github, Google, Facebook, ...) et CAS
 
> Des commandes consoles faciles à mettre en place pour les applications nécessitant de la maintenance journalière automatisée

</p>
</details>

<details>
<summary>👌 Trouvez-vous que ce projet soit assez mature ?</summary>
<p>

> N'hésitez pas à commenter dans les discussions.

</p>
</details>

---

Cette architecture MVC Objet est composée d'un moteur de template (Blade ou Twig) provenant d'un projet de Simon Asika (Ventoviro) -> https://github.com/windwalker-io/renderer.git

Les urls d'accès basiques sont de type `www.domain.tld/le_nom_de_mapage/varname1/varvalue1/varname2/varvalue2/ ...`

Avant toute chose après le clonage de cette branche et avant le lancement du docker, vous executerez les commandes suivantes:

```
composer  update
git submodule init
git submodule update
```

cela permettra de stabiliser les ressources publiques tierces, pour que tout fonctionne bien.

---

### Afin de créer une nouvelle page vous devez instancier trois fichiers contenant diverses variables, dont voici les adresses:

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
description : la description pour les moteurs de recherche
engine : none | blade | twig
authentification : no | yes
ariane : {acceuil, test d'acceuil}
arianelink : {index, le_nom_de_mapage}
paramsN : tout(s) paramètre(s) supplémentaire(s)
```
avec en plus de cela : 
`engine : blade` pour un layout blade ou `engine : twig` pour un layout twig. Dans le cas où l'engine est a `none` alors vous pouvez tout à fait afficher la page directement dans le controlleur,
seulement il vous faudra réaliser à la main le template l'affichant en recréant un systeme de chargement complet.

---

`application > include > vues > view > le_nom_de_mapage.blade.php` contenant le layout `blade` a instancier
`application > include > vues > view > le_nom_de_mapage.html.twig` contenant le layout `twig` a instancier

> Vous pouvez aussi tout à fait utiliser la commande:
> 
> `php console/bin.php page:add`

Pour les modules, c'est beaucoup plus compliqué : il faut instancier ces trois précédents fichiers en faisant appel la class Modular,
ne pas oublier de référencer le module dans le dossier modules > setup > registre.model, ajouter le dossier contenant le code du module
et faire correspondre le nom du controlleur frontal du module avec le registre.

> Mais bon, vous pouvez aussi tout à fait utiliser la commande:
> 
> `php console/bin.php module:add`

## Documentation

La documentation est dans `data/docs/`. La convention de nommage des fichiers et le choix des méthodes HTTP sont décrits dans [l'annexe 20](<data/docs/20. Annexe - Convention de nommage et méthodes HTTP.md>).

## 🙏 Montrez votre support

N'hésitez pas à mettre une ⭐ si ce projet vous a aidé.

## ❤️ Contributeurs

D'avance, merci aux futurs formidables contributeurs, et s'il veulent se manifester : je suis tout ouïe

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore -->
<!--<table>
  <tr>
    <td align="center"><a href="https://emmanuelroy.name/"><img src="https://avatars3.githubusercontent.com/u/9840435?v=4" width="100px;" alt="Emmanuel ROY"/><br /><sub><b>Emmanuel ROY</b></sub></a><br /><a href="https://github.com/Acksop/SAND-framework/commits?author=acksop" title="Project Owner">🎢</a></td>
  </tr>
</table> -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

Ce projet suit la spécification [all-contributors](https://github.com/all-contributors/all-contributors). Les contributions de tout type sont les bienvenues !

This project follows the [all-contributors](https://allcontributors.org) specification. Contributions of any kind are welcome!
