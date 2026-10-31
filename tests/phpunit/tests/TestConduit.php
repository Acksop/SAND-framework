<?php

/**
 * Conduit de test : comme les conduits de application/include/conduits, il est
 * global et ne déclare pas les paramètres de route qu'il reçoit (ici $id).
 * Ses gabarits viennent de tests/gabarits, avec un cache temporaire.
 */
// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace -- les conduits SAND sont globaux
class TestConduit extends \MVC\Classe\Implement\Conduit
{
    /** @var string Dossier de cache Blade utilisé par les tests. */
    public static $cache = '';

    /**
     * Route('/foo/{id}') : rend le gabarit conduit-test.
     */
    public function load()
    {
        return $this->render('conduit-test', ['id' => $this->id]);
    }

    /**
     * Titre de page transmis par initialize() depuis l'URL.
     */
    public function titre()
    {
        return $this->templateData['page_title'];
    }

    protected function racineVues(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'gabarits';
    }

    protected function cheminCache(): string
    {
        return self::$cache;
    }
}
