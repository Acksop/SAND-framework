<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Dispacher;
use MVC\Classe\Modele;
use PHPUnit\Framework\TestCase;

/**
 * Non-régression du routage Symfony (Dispacher et application/config/files/routing.yml),
 * de l'appel d'un conduit et de la lecture d'un fichier .model.
 */
final class RoutageTest extends TestCase
{
    /** @var string|null */
    private $uriAvant;
    /** @var array */
    private $getAvant;
    /** @var string */
    private $cache;

    protected function setUp(): void
    {
        $this->uriAvant = $_SERVER['REQUEST_URI'] ?? null;
        $this->getAvant = $_GET;
        $this->cache = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-conduit-' . uniqid();
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
        $_GET = $this->getAvant;
        if ($this->uriAvant === null) {
            unset($_SERVER['REQUEST_URI']);
        } else {
            $_SERVER['REQUEST_URI'] = $this->uriAvant;
        }
        foreach (glob($this->cache . DIRECTORY_SEPARATOR . '*') ?: [] as $fichier) {
            unlink($fichier);
        }
        if (is_dir($this->cache)) {
            rmdir($this->cache);
        }
    }

    public function testRouteSimple(): void
    {
        $route = $this->router('/foo');

        $this->assertSame('foo_route', $route['_route']);
        $this->assertSame('FooConduit::index', $route['controller']);
    }

    public function testRouteAvecParametre(): void
    {
        $route = $this->router('/foo/42');

        $this->assertSame('foo_placeholder_route', $route['_route']);
        $this->assertSame('FooConduit::load', $route['controller']);
        $this->assertSame('42', $route['id']);
    }

    public function testParametreHorsExigenceNeCorrespondPas(): void
    {
        $this->assertNull($this->router('/foo/abc'));
    }

    public function testRouteInconnue(): void
    {
        $this->assertNull($this->router('/route-inconnue'));
    }

    /**
     * Le Dispacher passe au matcher le chemin sans chaîne de requête :
     * une route suivie de « ?x=1 » correspond toujours.
     */
    public function testChaineDeRequeteCorrespond(): void
    {
        $route = $this->router('/foo?x=1');
        $this->assertSame('foo_route', $route['_route']);

        $route = $this->router('/foo/42?x=1&y');
        $this->assertSame('foo_placeholder_route', $route['_route']);
        $this->assertSame('42', $route['id']);
    }

    public function testAccueilAvecChaineDeRequeteSansRoute(): void
    {
        $this->assertNull($this->router('/?x=1'));
    }

    /**
     * UrlMatcher sans redirection : un slash final non déclaré dans
     * routing.yml ne correspond pas (comportement actuel, non modifié).
     */
    public function testSlashFinalNeCorrespondPas(): void
    {
        $this->assertNull($this->router('/foo/'));
        $this->assertNull($this->router('/foo/42/'));
    }

    public function testAccueilSansRoute(): void
    {
        $this->assertNull($this->router('/'));
    }

    public function testConduitRecoitLesParametresDeRoute(): void
    {
        $application = new \stdClass();
        $application->url = new \stdClass();
        $application->url->page = ['name' => 'foo_placeholder_route', 'page_title' => 'Foo'];
        $application->route = $this->router('/foo/7');
        \TestConduit::$cache = $this->cache;

        $conduit = new \TestConduit();
        $conduit->initialize($application);

        $this->assertSame('7', $conduit->id);
        $this->assertSame('Foo', $conduit->titre());
        $this->assertSame('<p>Conduit 7</p>', trim($conduit->load()));
    }

    public function testLectureDunModele(): void
    {
        $modele = new Modele(['name' => 'index', 'description' => '', 'params' => ['cle' => 'valeur']]);

        $this->assertSame('index', trim($modele->page['name']));
        $this->assertSame('twig', trim($modele->page['engine']));
        $this->assertSame('yes', trim($modele->page['authentification']));
        $this->assertSame(['acceuil'], $modele->page['ariane']);
        $this->assertSame(['cle' => 'valeur'], $modele->page['all_params']);
        $this->assertSame('valeur', $_GET['cle']);
    }

    /**
     * Résout une URL par le Dispacher et renvoie la route trouvée (ou null).
     */
    private function router(string $uri): ?array
    {
        $_SERVER['REQUEST_URI'] = $uri;

        return (new Dispacher())->route;
    }
}
