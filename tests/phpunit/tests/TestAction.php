<?php

/**
 * Action de test : rend ses gabarits depuis tests/gabarits et un cache temporaire.
 *
 * Elle est déclarée dans l'espace global, comme les actions de
 * application/include/actions, pour être appelée par
 * ControlleurAction::inserer('test.renderSalut', [...]).
 */
// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace -- les actions SAND sont globales
class TestAction extends \SAND\Classe\Implement\Action
{
    /** @var string Dossier de cache Blade utilisé par les tests. */
    public static $cache = '';

    /**
     * Rend le fragment action-test-salut avec le moteur demandé.
     */
    public function renderSalut($qui, $moteur = 'blade')
    {
        return $this->render('action-test-salut', ['qui' => $qui], $moteur);
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
