<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\ControlleurAction;
use PHPUnit\Framework\TestCase;

/**
 * ControlleurAction::inserer() : action introuvable ou en erreur (journalisée,
 * '' hors débogage), et 3e paramètre $dir de renderTwig()/renderBlade().
 */
final class ActionInsertionTest extends TestCase
{
    /** @var string Copie temporaire des gabarits (racine passée en $dir). */
    private $dossier;

    protected function setUp(): void
    {
        \TestAction::$cache = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-insertion-cache-' . uniqid();
        $this->dossier = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-insertion-' . uniqid();
        foreach (['view', 'layout', 'system'] as $sousDossier) {
            mkdir($this->dossier . DIRECTORY_SEPARATOR . $sousDossier, 0777, true);
        }
        $gabarits = [
            'blade.php' => '<p class="module">Module {{ $qui }}</p>',
            'html.twig' => '<p class="module">Module {{ qui }}</p>',
        ];
        foreach ($gabarits as $extension => $contenu) {
            file_put_contents(
                $this->dossier . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'action-module.' . $extension,
                $contenu
            );
        }
    }

    protected function tearDown(): void
    {
        $this->supprimer($this->dossier);
        $this->supprimer(\TestAction::$cache);
    }

    public function testUneActionIntrouvableRenvoieUneChaineVideEtEstJournalisee(): void
    {
        $resultat = null;
        $ajout = $this->journalPendant(function () use (&$resultat): void {
            $resultat = ControlleurAction::inserer('introuvableXyz.render', []);
        });

        $this->assertSame('', $resultat);
        $this->assertStringContainsString('IntrouvableXyzAction', $ajout);
        $this->assertStringContainsString('introuvable', $ajout);
        $this->assertStringNotContainsString(ACTION_PATH, $ajout, 'Chemin serveur journalisé hors débogage.');
    }

    public function testUnNomDActionInvalideNEstPasCharge(): void
    {
        $resultat = null;
        $ajout = $this->journalPendant(function () use (&$resultat): void {
            $resultat = ControlleurAction::inserer("mau/vais\n[faux]", []);
        });

        $this->assertSame('', $resultat);
        $this->assertStringContainsString('nom invalide', $ajout);
        $this->assertStringNotContainsString("\n[faux]", $ajout);
    }

    public function testUneActionQuiLeveUneExceptionRenvoieUneChaineVideHorsDebogage(): void
    {
        $this->assertFalse(\MVC\Object\Environment::debug(), 'Les tests tournent hors débogage.');

        $resultat = null;
        $ajout = $this->journalPendant(function () use (&$resultat): void {
            $resultat = ControlleurAction::inserer('echec.lever', ["panne\n[faux] ligne injectée"]);
        });

        $this->assertSame('', $resultat);
        $this->assertStringContainsString('EchecAction', $ajout);
        $this->assertStringContainsString('RuntimeException', $ajout);
        $this->assertStringContainsString('panne [faux] ligne', $ajout);
        $this->assertStringNotContainsString("\n[faux]", $ajout);
        $this->assertStringNotContainsString(__DIR__, $ajout, 'Chemin serveur journalisé hors débogage.');
    }

    public function testUneActionValideRendToujoursSonFragment(): void
    {
        $this->assertSame(
            '<p class="salut">Salut Ana</p>',
            trim((string) ControlleurAction::inserer('test.renderSalut', ['Ana']))
        );
    }

    public function testRenderTwigAccepteUneRacineDeVuesEnTroisiemeParametre(): void
    {
        $html = (new \TestAction())->renderTwig('action-module', ['qui' => 'Twig'], $this->dossier);

        $this->assertSame('<p class="module">Module Twig</p>', trim($html));
    }

    public function testRenderBladeAccepteUneRacineDeVuesEnTroisiemeParametre(): void
    {
        $html = (new \TestAction())->renderBlade('action-module', ['qui' => 'Blade'], $this->dossier);

        $this->assertSame('<p class="module">Module Blade</p>', trim($html));
        $this->assertNotEmpty(glob($this->dossier . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . '*.php'));
    }

    public function testSansTroisiemeParametreLaRacineResteRacineVues(): void
    {
        $html = (new \TestAction())->renderTwig('action-test-salut', ['qui' => 'Zoé']);

        $this->assertSame('<p class="salut">Salut Zoé</p>', trim($html));
    }

    /**
     * Exécute $action et renvoie ce qu'elle a ajouté à app.error.log.
     */
    private function journalPendant(callable $action): string
    {
        $journal = LOG_PATH . DIRECTORY_SEPARATOR . 'app.error.log';
        clearstatcache();
        $tailleAvant = is_file($journal) ? filesize($journal) : 0;
        $agentAvant = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $_SERVER['HTTP_USER_AGENT'] = 'phpunit';
        try {
            $action();
        } finally {
            if ($agentAvant === null) {
                unset($_SERVER['HTTP_USER_AGENT']);
            } else {
                $_SERVER['HTTP_USER_AGENT'] = $agentAvant;
            }
        }
        clearstatcache();

        return is_file($journal) ? (string) file_get_contents($journal, false, null, $tailleAvant) : '';
    }

    /**
     * Supprime récursivement un dossier temporaire du test.
     */
    private function supprimer(string $dossier): void
    {
        if ($dossier === '' || !is_dir($dossier)) {
            return;
        }
        foreach (glob($dossier . DIRECTORY_SEPARATOR . '*') ?: [] as $chemin) {
            is_dir($chemin) ? $this->supprimer($chemin) : unlink($chemin);
        }
        rmdir($dossier);
    }
}
