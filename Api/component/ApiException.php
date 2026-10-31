<?php

/**
 * Package MVC\Api
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 */

namespace MVC\Api\Component;

/**
 * Erreur HTTP voulue (404, 401, 405, 501...) : son message est renvoyé au client.
 * Toute autre exception devient un 500 générique (détail journalisé).
 */
class ApiException extends \RuntimeException
{
    /**
     * @param array<string, string> $entetes en-têtes HTTP à joindre à la réponse
     */
    public function __construct(string $message, int $statut, private array $entetes = [])
    {
        parent::__construct($message, $statut);
    }

    public function statut(): int
    {
        return $this->getCode();
    }

    /**
     * @return array<string, string>
     */
    public function entetes(): array
    {
        return $this->entetes;
    }
}
