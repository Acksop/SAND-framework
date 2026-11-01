# API REST de SAND (`api/`)

Dispatcher HTTP calqué sur `wp-json` de WordPress. Toute requête dont le chemin commence par `/wp-json` ou `/api` est traitée par `MVC\Api\Component\Router`, **avant** le routage MVC et la maintenance (`Application`). La réponse est toujours en JSON, erreurs comprises.

Documentation complète : `data/docs/18. API wp-json et Ereferer.md`.

## Arborescence

```
api/
├── component/      MVC\Api\Component\   Router, Requete, Response, Auth, RouteRegistry, Arguments, FabriqueDepot, ApiException
├── contract/       MVC\Api\Contract\    DepotSoumissions (à implémenter par l'hôte), SoumissionNonModifiable
├── controller/     MVC\Api\Controller\  AbstractController, v1\Ereferer, wp\v2\{Posts,Pages,Categories,Comments,Tags}
└── config/         routes.php (aucune route active), api_keys.php.skel (modèle, la copie n'est jamais versionnée)
```

## Correspondance URL / action

| Requête | Action |
|---|---|
| `GET /wp-json/wp/v2/posts` | `wp\v2\Posts::getItems()` |
| `GET /wp-json/wp/v2/posts/45` | `wp\v2\Posts::getItem('45')` |
| `POST /api/v1/ereferer` | `v1\Ereferer::createItem($body)` |
| `PUT` ou `PATCH /api/v1/x/45` | `updateItem('45', $body)` |
| `DELETE /api/v1/x/45` | `deleteItem('45')` |
| `OPTIONS …` | `schema()` |

Une action absente donne 405, avec l'en-tête `Allow`. Une ressource inconnue donne 404.

## Authentification

`GET`, `HEAD` et `OPTIONS` sont publics. Les autres méthodes exigent `Authorization: Bearer <clé>`. Une clé absente ou invalide donne un 401, avec le même message dans les deux cas.

```bash
cp api/config/api_keys.php.skel api/config/api_keys.php
php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"   # clé à transmettre au client
```

Dans `api_keys.php`, remplacez la valeur factice par cette clé, ou mieux par son empreinte SHA-256. La valeur factice du modèle n'est jamais acceptée.
