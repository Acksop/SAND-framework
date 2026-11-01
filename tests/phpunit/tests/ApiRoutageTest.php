<?php

declare(strict_types=1);

namespace Tests;

use SAND\Api\Component\Requete;
use SAND\Api\Component\Response;
use SAND\Api\Component\RouteRegistry;
use SAND\Api\Component\Router;
use SAND\Api\Component\Auth;
use SAND\Classe\Application;
use PHPUnit\Framework\TestCase;

/**
 * API wp-json : résolution des chemins, réponses JSON, priorité dans Application.
 */
final class ApiRoutageTest extends TestCase
{
    private function router(?RouteRegistry $registre = null): Router
    {
        return new Router(new Auth([]), $registre ?? new RouteRegistry());
    }

    private function traiter(string $methode, string $uri, string $corps = ''): Response
    {
        return $this->router()->traiter(new Requete($methode, $uri, $corps));
    }

    /**
     * @dataProvider ressourcesWp
     */
    public function testRessourcesWpRepondent404Json(string $ressource, string $liste, string $element): void
    {
        $attendus = ['/wp-json/wp/v2/' . $ressource => $liste, '/wp-json/wp/v2/' . $ressource . '/45' => $element];
        foreach ($attendus as $uri => $message) {
            $reponse = $this->traiter('GET', $uri);
            $this->assertSame(404, $reponse->statut(), $uri);
            $this->assertTrue($reponse->donnees()['error']);
            $this->assertStringContainsString($message, $reponse->donnees()['message']);
        }
    }

    public function ressourcesWp(): array
    {
        return [
            'posts' => ['posts', 'Posts introuvables', 'Post non référencé'],
            'pages' => ['pages', 'Pages introuvables', 'Post non référencé'],
            'categories' => ['categories', 'Categories introuvables', 'Post non référencé'],
            'comments' => ['comments', 'Commentaires introuvables', 'Post non référencé'],
            'tags' => ['tags', 'Tags introuvables', 'Tag non référencé'],
        ];
    }

    /**
     * @dataProvider cheminsInconnus
     */
    public function testCheminInconnuDonne404Json(string $uri): void
    {
        $reponse = $this->traiter('GET', $uri);
        $this->assertSame(404, $reponse->statut());
        $this->assertTrue($reponse->donnees()['error']);
    }

    public function cheminsInconnus(): array
    {
        return [
            'ressource inconnue' => ['/wp-json/wp/v2/inconnue'],
            'espace inconnu' => ['/api/v9/ereferer'],
            'racine' => ['/wp-json/'],
            'sans ressource' => ['/wp-json/v1'],
            'segment invalide' => ['/wp-json/v1/Ere%5Cferer'],
            'trop de segments' => ['/api/v1/ereferer/1/2'],
            'espace non versionné' => ['/api/Component/router'],
            'pas une API' => ['/index'],
        ];
    }

    public function testEreferer405EnGet(): void
    {
        foreach (['/wp-json/v1/ereferer', '/api/v1/ereferer'] as $uri) {
            $reponse = $this->traiter('GET', $uri);
            $this->assertSame(405, $reponse->statut(), $uri);
            $this->assertSame('POST, OPTIONS', $reponse->entetes()['Allow']);
            $this->assertStringNotContainsString('GET', $reponse->donnees()['message']);
        }
    }

    public function testOptionsRenvoieLeSchema(): void
    {
        $reponse = $this->traiter('OPTIONS', '/wp-json/v1/ereferer');
        $this->assertSame(200, $reponse->statut());
        $noms = array_column($reponse->donnees()['available_actions'], 'name');
        $this->assertSame('ereferer', $reponse->donnees()['resource']);
        $this->assertStringNotContainsString('SAND\\\\', (string) json_encode($reponse->donnees()));
        $this->assertContains('createItem', $noms);
    }

    public function testParametreSansEgalEstTolere(): void
    {
        $requete = new Requete('GET', '/wp-json/wp/v2/posts?page&per_page=5&body=x', '{"a":1}');
        $this->assertSame(['page' => '', 'per_page' => '5', 'body' => ['a' => 1]], $requete->parametres());
        $this->assertSame(404, $this->router()->traiter($requete)->statut());
    }

    public function testRouteExpliciteAvantLaConvention(): void
    {
        $registre = new RouteRegistry();
        $registre->register('GET', 'v1/exemples/(?P<id>\d+)', static function (array $params, array $corps): array {
            return ['id' => $params['id'], 'corps' => $corps];
        });
        $reponse = $this->router($registre)->traiter(new Requete('GET', '/api/v1/exemples/12'));
        $this->assertSame(200, $reponse->statut());
        $this->assertSame(['id' => '12', 'corps' => []], $reponse->donnees());
    }

    public function testFichierDeRoutesLivreSansRouteActive(): void
    {
        $registre = RouteRegistry::depuisFichier(API_FICHIER_ROUTES);
        $this->assertSame(0, $registre->nombre());
    }

    public function testCheminApi(): void
    {
        $this->assertSame('wp/v2/posts', Requete::cheminApi('/wp-json/wp/v2/posts?x=1'));
        $this->assertSame('v1/ereferer', Requete::cheminApi('/api/v1/ereferer/'));
        $this->assertSame('', Requete::cheminApi('/wp-json'));
        $this->assertNull(Requete::cheminApi('/index'));
        $this->assertNull(Requete::cheminApi('/apiculture'));
        $this->assertNull(Requete::cheminApi('/page/wp-json/x'));
        $this->assertSame('v1/ereferer', Requete::cheminApi('//wp-json//v1/ereferer'));
        $this->assertNull(Requete::cheminApi('/wp-json%2Fv1%2Fereferer'));
        $this->assertNull(Requete::cheminApi('/WP-JSON/v1/ereferer'));
    }

    public function testEmissionJson(): void
    {
        ob_start();
        Response::erreur('Introuvable', 404)->emettre();
        $sortie = (string) ob_get_clean();
        $this->assertSame(['error' => true, 'message' => 'Introuvable', 'context' => []], json_decode($sortie, true));
    }

    public function testApplicationDelegueLApiSansControlleur(): void
    {
        $serveurAvant = $_SERVER;
        $_SERVER['REQUEST_URI'] = '/wp-json/wp/v2/posts';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        try {
            $application = new Application();
            ob_start();
            $application->launch();
            $sortie = (string) ob_get_clean();
        } finally {
            $_SERVER = $serveurAvant;
        }
        $this->assertTrue($application->api);
        $this->assertNull($application->controlleur);
        $this->assertNull($application->url);
        $this->assertStringContainsString('Posts introuvables', json_decode($sortie, true)['message']);
    }

    public function testApplicationGardeLeCheminMvcPourIndex(): void
    {
        $this->assertFalse(Application::estRequeteApi('/index'));
        $this->assertFalse(Application::estRequeteApi('/'));
        $this->assertTrue(Application::estRequeteApi('/api/v1/ereferer'));

        $serveurAvant = $_SERVER;
        $_SERVER['REQUEST_URI'] = '/index';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        try {
            $application = new Application();
        } finally {
            $_SERVER = $serveurAvant;
        }
        $this->assertFalse($application->api);
        $this->assertSame('index', $application->url->page['name']);
    }

    public function testHtaccessEnvoieLApiVersIndexEtTransmetAuthorization(): void
    {
        $htaccess = (string) file_get_contents(dirname(__DIR__, 3) . '/public/.htaccess');
        $this->assertMatchesRegularExpression('/^\s*RewriteRule \. index\.php \[L\]/m', $htaccess);
        $this->assertMatchesRegularExpression(
            '#RewriteRule \^\(wp-json\|api\)\(/\|\$\) - \[E=HTTP_AUTHORIZATION:%\{HTTP:Authorization\}\]#',
            $htaccess
        );
        $this->assertStringContainsString('CGIPassAuth On', $htaccess);
    }
}
