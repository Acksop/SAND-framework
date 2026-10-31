<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Controlleur;
use MVC\Classe\Implement\HttpReponse;
use MVC\Classe\Implement\RestReponse;
use MVC\Classe\Url;
use MVC\Classe\UrlResolveur;
use PHPUnit\Framework\TestCase;

/**
 * Corrections du routage reportées depuis un site hôte :
 * chaîne de requête relue dans $_GET, requête « App » sans réponse dédiée,
 * erreur de configuration sans chemins en production, liens sans paramètres
 * et constructeur des réponses REST/HTTP.
 */
final class UrlRoutageTest extends TestCase
{
    /** @var string|null */
    private $uriAvant;
    /** @var array */
    private $getAvant;

    protected function setUp(): void
    {
        $this->uriAvant = $_SERVER['REQUEST_URI'] ?? null;
        $this->getAvant = $_GET;
        // avertissements et dépréciations deviennent des exceptions
        set_error_handler(
            static function (int $niveau, string $message, string $fichier = '', int $ligne = 0): bool {
                throw new \ErrorException($message, 0, $niveau, $fichier, $ligne);
            },
            E_WARNING | E_NOTICE | E_DEPRECATED | E_USER_DEPRECATED
        );
    }

    protected function tearDown(): void
    {
        restore_error_handler();
        $_GET = $this->getAvant;
        if ($this->uriAvant === null) {
            unset($_SERVER['REQUEST_URI']);
        } else {
            $_SERVER['REQUEST_URI'] = $this->uriAvant;
        }
    }

    public function testChaineDeRequeteRelueSansEcraserGet(): void
    {
        $_GET = ['a' => 'deja'];
        $_SERVER['REQUEST_URI'] = '/index?a=1&b&c=trois%20mots';

        new Url('GET', false);

        $this->assertSame('deja', $_GET['a']);
        $this->assertSame('', $_GET['b']);
        $this->assertSame('trois mots', $_GET['c']);
    }

    public function testChaineDeRequeteAvecTableaux(): void
    {
        $_GET = ['t' => ['deja']];
        $_SERVER['REQUEST_URI'] = '/index?a[]=1&a[]=2&t[]=autre&t=scalaire';

        new Url('GET', false);

        $this->assertSame(['1', '2'], $_GET['a']);
        $this->assertSame(['deja'], $_GET['t']);
    }

    /**
     * Ligne 19 : avec BASE_SERVER_DIRECTORY = "my-app/", explode() donne
     * ['my-app', '']. Pour l'URL « /my-app », le premier segment retire
     * 'my-app' et vide le chemin ; le second (chaîne vide) ne doit plus lire
     * $urlParts[0] inexistant (avertissement « Undefined array key 0 »).
     */
    public function testRacineDuSousRepertoireSansAvertissement(): void
    {
        $methode = new \ReflectionMethod(Url::class, 'retirerSousRepertoires');

        $this->assertSame([], $methode->invoke(null, ['my-app'], 'my-app/'));
        $this->assertSame(['page'], $methode->invoke(null, ['my-app', 'page'], 'my-app/'));
    }

    public function testRequeteAppSansReponseServieCommePageMvc(): void
    {
        $_SERVER['REQUEST_URI'] = '/index';

        $url = new Url('GET', true);

        $this->assertSame('index', $url->page['name']);
        $this->assertSame(CONTROLLERS_PATH . DIRECTORY_SEPARATOR . 'index.php', $url->pageFile);
    }

    public function testEstFichierReponse(): void
    {
        $methode = new \ReflectionMethod(Controlleur::class, 'estFichierReponse');
        $dossier = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-reponse-' . uniqid();
        mkdir($dossier);
        $reponse = $dossier . DIRECTORY_SEPARATOR . 'FooHttpReponse.php';
        touch($reponse);

        try {
            $this->assertTrue($methode->invoke(null, $reponse));
            $this->assertFalse($methode->invoke(null, $dossier . DIRECTORY_SEPARATOR . 'BarRestReponse.php'));
            $this->assertFalse($methode->invoke(null, CONTROLLERS_PATH . DIRECTORY_SEPARATOR . 'index.php'));
        } finally {
            unlink($reponse);
            rmdir($dossier);
        }
    }

    public function testErreurDeConfigurationSansCheminsEnProduction(): void
    {
        $detail = "contrôleur absent : /srv/secret/error.php";

        $message = new \ReflectionMethod(UrlResolveur::class, 'messageErreurConfiguration');
        $production = $message->invoke(null, $detail, false);
        $debogage = $message->invoke(null, $detail, true);

        $this->assertStringNotContainsString('/srv/secret', $production);
        $this->assertNotSame('', $production);
        $this->assertSame($detail, $debogage);
    }

    public function testLienSansParametres(): void
    {
        $this->assertSame('/' . BASE_SERVER_DIRECTORY . 'page', Url::link_rewrite(false, 'page', null));
        $this->assertSame('/' . BASE_SERVER_DIRECTORY . 'page/a/1', Url::link_rewrite(false, 'page', ['a' => 1]));
    }

    public function testConstructeurDesReponses(): void
    {
        $url = new \stdClass();
        $url->page = ['params' => ['id' => '7']];
        $data = ['cle' => 'valeur'];

        foreach ([RestReponse::class, HttpReponse::class] as $classe) {
            $reponse = new $classe($url, $data);
            $this->assertSame(['id' => '7'], $reponse->params, $classe);
            $this->assertSame($data, $reponse->data, $classe);
            $this->assertSame($url, $reponse->url, $classe);

            $ancienne = new $classe();
            $this->assertNull($ancienne->params, $classe);
            $ancienne->instanciate($url, $data);
            $this->assertSame(['id' => '7'], $ancienne->params, $classe);
        }
    }
}
