<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Component;

/**
 * Réponse de l'API : toujours du JSON.
 * Les contrôleurs la renvoient, le routeur l'émet (pas de sortie directe ni de die()).
 */
final class Response
{
    private const OPTIONS_JSON = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE;

    /**
     * @param array<mixed>          $donnees
     * @param array<string, string> $entetes
     */
    public function __construct(
        private array $donnees,
        private int $statut = 200,
        private array $entetes = []
    ) {
    }

    /**
     * Réponse d'erreur au format historique de l'API : error, message, context.
     *
     * @param array<string, string> $entetes
     */
    public static function erreur(string $message, int $statut, array $entetes = []): self
    {
        return new self(['error' => true, 'message' => $message, 'context' => []], $statut, $entetes);
    }

    /**
     * @return array<mixed>
     */
    public function donnees(): array
    {
        return $this->donnees;
    }

    public function statut(): int
    {
        return $this->statut;
    }

    /**
     * @return array<string, string>
     */
    public function entetes(): array
    {
        return $this->entetes;
    }

    /**
     * @param bool $sansCorps vrai pour une requête HEAD : en-têtes seulement
     */
    public function emettre(bool $sansCorps = false): void
    {
        if (!headers_sent()) {
            http_response_code($this->statut);
            header('Content-Type: application/json; charset=utf-8');
            foreach ($this->entetes as $nom => $valeur) {
                header($nom . ': ' . $valeur);
            }
        }
        if (!$sansCorps) {
            echo json_encode($this->donnees, self::OPTIONS_JSON);
        }
    }
}
