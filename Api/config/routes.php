<?php

/**
 * Routes explicites de l'API, hors convention /{namespace}/{resource}/{id?}
 * (équivalent du hook rest_api_init de WordPress). Essayées avant la convention.
 *
 * Ce fichier est chargé par RouteRegistry::depuisFichier() : le registre est
 * disponible dans $registre. Le motif est relatif au préfixe /wp-json/ ou /api/.
 * Le rappel reçoit les paramètres nommés du motif et le corps JSON décodé ;
 * il renvoie un tableau (200) ou une MVC\Api\Component\Response.
 *
 * Attention : une route GET (ou HEAD) est publique, sans clé d'API, comme toute
 * lecture. Déclarer en POST (clé exigée) une route qui expose des données réservées,
 * ou vérifier le droit d'accès dans le rappel.
 *
 * Aucune route n'est active par défaut. Exemple, en requête préparée :
 *
 * use MVC\Api\Component\Response;
 *
 * $registre->register('GET', 'v1/articles/(?P<id>\d+)', function (array $params, array $corps) {
 *     $bdd = new \MVC\Classe\Bdd('default');
 *     $requete = $bdd->faireBindRequete(
 *         'SELECT id, titre FROM articles WHERE id = :id',
 *         [['id', (int) $params['id'], \PDO::PARAM_INT]]
 *     );
 *     $lignes = $requete === 0 ? [] : $bdd->exploiterResultat($requete);
 *     if ($lignes === []) {
 *         return Response::erreur('Article introuvable', 404);
 *     }
 *     return ['article' => $lignes[0]];
 * });
 */
