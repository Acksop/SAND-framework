<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Component;

/**
 * Filtre et convertit les paramètres de la requête selon la signature
 * de l'action appelée (nom et type de chaque paramètre).
 */
final class Arguments
{
    /**
     * @param array<string, mixed> $parametres chaîne de requête, « id » et « body »
     * @return array<string, mixed> arguments nommés, prêts pour ...$args
     */
    public static function pourMethode(string $classe, string $methode, array $parametres): array
    {
        $arguments = [];
        foreach ((new \ReflectionMethod($classe, $methode))->getParameters() as $parametre) {
            $nom = $parametre->getName();
            if (array_key_exists($nom, $parametres)) {
                // absent : la valeur par défaut de la méthode s'applique
                $arguments[$nom] = self::convertir($parametres[$nom], $parametre);
            }
        }

        return $arguments;
    }

    private static function convertir(mixed $valeur, \ReflectionParameter $parametre): mixed
    {
        $type = $parametre->getType();
        if (!$type instanceof \ReflectionNamedType) {
            return $valeur;
        }

        return match ($type->getName()) {
            'int' => is_scalar($valeur) ? (int) $valeur : 0,
            'float' => is_scalar($valeur) ? (float) $valeur : 0.0,
            'bool' => filter_var($valeur, FILTER_VALIDATE_BOOLEAN),
            'array' => is_array($valeur) ? $valeur : [$valeur],
            'string' => is_scalar($valeur) ? (string) $valeur : '',
            default => $valeur,
        };
    }
}
