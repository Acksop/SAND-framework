<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Rendu Blade et Twig par Implement\Action : héritage view > layout > system,
 * appel d'action depuis un gabarit, cache Blade, absence de dépréciation.
 */
final class RenduTest extends TestCase
{
    /** @var \TestAction */
    private $action;

    protected function setUp(): void
    {
        \TestAction::$cache = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-rendu-' . uniqid();
        $this->action = new \TestAction();
        // Toute dépréciation émise pendant un rendu fait échouer le test.
        set_error_handler(
            static function (int $niveau, string $message, string $fichier = '', int $ligne = 0): bool {
                throw new \ErrorException('Dépréciation : ' . $message, 0, $niveau, $fichier, $ligne);
            },
            E_DEPRECATED | E_USER_DEPRECATED
        );
    }

    protected function tearDown(): void
    {
        restore_error_handler();
        $this->supprimer(\TestAction::$cache);
    }

    public function testBladeRendLaVueDansLeLayoutEtLeSysteme(): void
    {
        $html = $this->action->renderBlade('page', ['message' => 'Bonjour Blade']);

        $this->assertMatchesRegularExpression(
            '#<html><body>\s*<main><h1>Bonjour Blade</h1>\s*</main>\s*</body></html>#',
            $html
        );
    }

    public function testTwigRendLaVueDansLeLayoutEtLeSysteme(): void
    {
        $html = $this->action->renderTwig('page', ['message' => 'Bonjour Twig']);

        $this->assertMatchesRegularExpression(
            '#<html><body>\s*<main><h1>Bonjour Twig</h1></main>\s*</body></html>#',
            $html
        );
    }

    public function testLeLayoutPasseAvantLeSysteme(): void
    {
        $this->assertSame('priorite-layout', trim($this->action->renderBlade('priorite', [])));
        $this->assertSame('priorite-layout', trim($this->action->renderTwig('priorite', [])));
    }

    public function testUneVueBladeAppelleUneAction(): void
    {
        $html = $this->action->renderBlade('page-action', ['qui' => 'Ana']);

        $this->assertStringContainsString('<main>', $html);
        $this->assertStringContainsString('<p class="salut">Salut Ana</p>', $html);
    }

    public function testUneVueTwigAppelleUneAction(): void
    {
        $html = $this->action->renderTwig('page-action', ['qui' => 'Léo']);

        $this->assertStringContainsString('<main>', $html);
        $this->assertStringContainsString('<p class="salut">Salut Léo</p>', $html);
    }

    public function testLeCacheBladeEstRempliEtReutilise(): void
    {
        $premier = $this->action->renderBlade('page', ['message' => 'Cache']);
        $fichiers = glob(\TestAction::$cache . DIRECTORY_SEPARATOR . '*.php');

        $this->assertNotEmpty($fichiers, 'Le cache Blade est vide après rendu.');
        $this->assertSame($premier, $this->action->renderBlade('page', ['message' => 'Cache']));
    }

    public function testLaFonctionParDefautRendEnBlade(): void
    {
        $this->assertSame('<p class="salut">Salut Zoé</p>', trim($this->action->renderSalut('Zoé')));
    }

    /**
     * Supprime le dossier de cache temporaire d'un test.
     */
    private function supprimer(string $dossier): void
    {
        if ($dossier === '' || !is_dir($dossier)) {
            return;
        }
        foreach (glob($dossier . DIRECTORY_SEPARATOR . '*') ?: [] as $fichier) {
            unlink($fichier);
        }
        rmdir($dossier);
    }
}
