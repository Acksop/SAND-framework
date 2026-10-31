<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Controller\wp\v2;

use MVC\Api\Component\ApiException;
use MVC\Api\Component\Response;
use MVC\Api\Controller\AbstractController;

/**
 * Ressource WordPress fictive /wp-json/wp/v2/tags : répond 404 en JSON
 * (le site n'est pas un WordPress) ; les écritures répondent 501.
 */
class Tags extends AbstractController
{
    /**
     * GET /wp-json/wp/v2/tags (paramètres page, per_page)
     */
    public static function getItems(int $page = 1, int $per_page = 20): Response
    {
        return Response::erreur("Tags introuvables, ceci n'est pas du Worpress et je suis une Mouette !", 404);
    }

    /**
     * GET /wp-json/wp/v2/tags/{id}
     */
    public static function getItem(string $id): Response
    {
        return Response::erreur("Tag non référencé, ceci n'est pas du Worpress et je suis une Mouette !", 404);
    }

    public static function createItem(array $body = []): Response
    {
        throw new ApiException('createItem non implémenté pour Tags', 501);
    }

    public static function updateItem(string $id, array $body = []): Response
    {
        throw new ApiException('updateItem non implémenté pour Tags', 501);
    }

    public static function deleteItem(string $id): Response
    {
        throw new ApiException('deleteItem non implémenté pour Tags', 501);
    }
}
