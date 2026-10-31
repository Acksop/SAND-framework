<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Controller;

/**
 * Base commune des contrôleurs d'API.
 *
 * Actions reconnues par le routeur (méthodes publiques statiques) : getItems,
 * getItem, createItem, updateItem, deleteItem. Chacune renvoie un tableau
 * (réponse 200) ou une MVC\Api\Component\Response.
 *
 * schema() répond à OPTIONS : il décrit les actions à partir de leur PHPDoc.
 */
abstract class AbstractController
{
    /** Actions décrites par schema() (les autres méthodes publiques sont ignorées). */
    public const ACTIONS = ['getItems', 'getItem', 'createItem', 'updateItem', 'deleteItem'];

    /**
     * @return array{resource: string, available_actions: array<int, array{name: string, description: ?string}>}
     */
    public static function schema(): array
    {
        $reflexion = new \ReflectionClass(static::class);
        $actions = [];
        foreach ($reflexion->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC) as $methode) {
            if (!in_array($methode->getName(), self::ACTIONS, true)) {
                continue;
            }
            $actions[] = [
                'name' => $methode->getName(),
                'description' => self::decrire($methode),
            ];
        }

        return [
            'resource' => static::class,
            'available_actions' => $actions,
        ];
    }

    /**
     * Texte libre du PHPDoc d'une méthode, sans les marqueurs ni les tags (@param...).
     */
    private static function decrire(\ReflectionMethod $methode): ?string
    {
        $doc = $methode->getDocComment();
        if ($doc === false) {
            return null;
        }
        $doc = (string) preg_replace(['#^/\*\*#', '#\*/$#'], '', $doc);
        $lignes = [];
        foreach (explode("\n", $doc) as $ligne) {
            $ligne = trim((string) preg_replace('#^\s*\*\s?#', '', $ligne));
            if ($ligne !== '' && !str_starts_with($ligne, '@')) {
                $lignes[] = $ligne;
            }
        }

        return $lignes !== [] ? implode(' ', $lignes) : null;
    }
}
