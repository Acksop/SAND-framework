# Le contrôleur de base le plus simple possible

il vous faut instancier deux fichiers sous cette forme:

* application/include/modeles/name.model
```
name : name
page_title : Page de l'application
description : Description de la page
params1 : {val1,val2}
params2 : val
```
il faut absolument renseigner le name avec le nom générique de la page, page_title permet de modifier le contenu de la balise html title et description permet de modifier le contenu de la balise meta description. Enfin les parametres suivant sont optionnels et permet de passer des valeur dans le controlleur ou dans la vue.

* application/include/controlleurs/name.php
```php
<?php
echo <<<EOD
 Ce que vous voulez afficher en HTML
EOD;
```
par exemple les fichiers simple au niveau textuel du genre les CGU ou la Politique de confidentialité