# Documentation sur le developement dirigé par le Domaine Métier

https://alexsoyes.com/ddd-domain-driven-design/
https://manifesto.softwarecraftsmanship.org/#/fr-fr
https://refactoring.guru/fr/design-patterns
https://refactoring.guru/fr/design-patterns/catalog



# Objectif DDD : Faire un projet au plus proche du métier

Le Domain Driven Design veille à ce que le modèle de données du métier soit exprimé dans le langage métier (mais aussi dans le code).

Langage métier défini ensemble, par le business ET les développeurs.

Cela implique parfois de changer d’architecture, mais surtout de communiquer davantage en amont avec le métier.

Le Domain Driven Design est une manière de penser et de voir le projet, il doit être drivé par une réelle envie retranscrire le savoir du métier directement dans le code.

# Qui a inventé le DDD ?

Le DDD a été créé par Eric Evans en 2003 suite à la sortie de son livre « Le blue book », « Tackling Complexity in the Heart of Software ».

Il présente ce livre comme étant le fruit de 20 années de bonnes pratiques de développement tirées au sein de la communauté de la programmation orientée objet et des artisans développeurs (https://manifesto.softwarecraftsmanship.org/#/fr-fr).

# Pourquoi utiliser DDD ? ✅

> Quand l’application est techniquement complexe ou que son métier l’est

> S’il y a un haut risque dans le métier (comme le secteur bancaire)

> S'il y a une grosse valeur dans le métier

# Pourquoi ne pas utiliser DDD ? ❌

> Un simple CRUD

> S’il s’agit de gérer du contenu (CMS & Cie)

> Pas ou peu de maintenance, de besoin d’évolution

> Une application techniquement simple

> Un domaine très générique ou peu de complexité métier

> « Time To Market » rapide (il faut livrer rapidement)

> Une équipe de développeurs trop jeunes