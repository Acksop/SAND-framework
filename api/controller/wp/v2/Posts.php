<?php

/**
 * Package SAND\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace SAND\Api\Controller\wp\v2;

use SAND\Api\Component\ApiException;
use SAND\Api\Component\Response;
use SAND\Api\Controller\AbstractController;

/**
 * Ressource WordPress fictive /wp-json/wp/v2/posts : répond 404 en JSON
 * (le site n'est pas un WordPress) ; les écritures répondent 501.
 */
class Posts extends AbstractController
{
    /**
     * GET /wp-json/wp/v2/posts (paramètres page, per_page)
     */
    public static function getItems(int $page = 1, int $per_page = 20): Response
    {
        return Response::erreur("Posts introuvables, ceci n'est pas du WordPress et je suis une Mouette !", 404);
    }

    /**
     * GET /wp-json/wp/v2/posts/{id}
     */
    public static function getItem(string $id): Response
    {
        return Response::erreur("Post non référencé, ceci n'est pas du WordPress et je suis une Mouette !", 404);
    }

    public static function createItem(array $body = []): Response
    {
        throw new ApiException('createItem non implémenté pour Posts', 501);
    }

    public static function updateItem(string $id, array $body = []): Response
    {
        throw new ApiException('updateItem non implémenté pour Posts', 501);
    }

    public static function deleteItem(string $id): Response
    {
        throw new ApiException('deleteItem non implémenté pour Posts', 501);
    }
}
