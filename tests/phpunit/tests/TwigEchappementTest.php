<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Rendu;
use PHPUnit\Framework\TestCase;

/**
 * Échappement automatique de Twig 3 et fonctions SAND : action() insère un
 * fragment HTML sûr, session() et server() restent échappées.
 */
final class TwigEchappementTest extends TestCase
{
    /** @var \TestAction */
    private $action;
    /** @var array|null */
    private $sessionAvant;
    /** @var array */
    private $serverAvant;

    protected function setUp(): void
    {
        \TestAction::$cache = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-twig-' . uniqid();
        $this->action = new \TestAction();
        $this->sessionAvant = $_SESSION ?? null;
        $this->serverAvant = $_SERVER;
    }

    protected function tearDown(): void
    {
        if ($this->sessionAvant === null) {
            unset($_SESSION);
        } else {
            $_SESSION = $this->sessionAvant;
        }
        $_SERVER = $this->serverAvant;
    }

    public function testActionInsereLeFragmentHtmlSansRaw(): void
    {
        // page-action.html.twig écrit {{ action(...) }}, sans |raw.
        $html = $this->action->renderTwig('page-action', ['qui' => '<i>Léo</i>']);

        $this->assertStringContainsString('<p class="salut">Salut ', $html);
        $this->assertStringNotContainsString('&lt;p class=', $html);
        // La donnée passée à l'action reste échappée par le gabarit de l'action.
        $this->assertStringContainsString('Salut &lt;i&gt;Léo&lt;/i&gt;</p>', $html);
    }

    public function testSessionEtServerRestentEchappees(): void
    {
        $_SESSION = ['cle_test' => '<b>s</b>'];
        $_SERVER['CLE_TEST'] = '<b>v</b>';

        $html = $this->action->renderTwig('echappement-twig', []);

        $this->assertSame('session:&lt;b&gt;s&lt;/b&gt;|server:&lt;b&gt;v&lt;/b&gt;', trim($html));
    }

    public function testLIndexTwigDuFrameworkAfficheLActionSansEchappement(): void
    {
        // Vrai gabarit application/include/vues/view/index.html.twig (ligne 9 :
        // {{ action('default.defaultTwig',[4,5,6]) }}), rendu en lecture seule.
        $_SERVER['SERVER_NAME'] = 'localhost';
        $donnees = [
            'name' => 'index',
            'page_title' => 'Accueil',
            'description' => 'Test',
            'templating_a' => 'a',
            'templating_b' => 'b',
            'templating_c' => 'c',
        ];

        $html = Rendu::rendre('twig', 'index', $donnees);

        $this->assertStringContainsString('a::b::c', $html);
        $this->assertStringContainsString('4::5::6', $html);
        $this->assertStringNotContainsString('&lt;', $html);
    }
}
