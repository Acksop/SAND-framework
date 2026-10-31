<?php

declare(strict_types=1);

namespace Tests;

use MVC\Api\Component\Auth;
use MVC\Api\Component\Requete;
use MVC\Api\Component\Response;
use MVC\Api\Component\RouteRegistry;
use MVC\Api\Component\Router;
use MVC\Api\Controller\v1\Ereferer;
use MVC\Classe\Application;
use PHPUnit\Framework\TestCase;

/**
 * Durcissements de l'API : clés, lecture du corps, limites Ereferer,
 * méthodes et chemins détournés, journal, maintenance.
 */
final class ApiDurcissementTest extends TestCase
{
    private string $cle;
    private Router $router;
    private DepotSoumissionsFactice $depot;
    private array $serveurAvant;

    protected function setUp(): void
    {
        $this->serveurAvant = $_SERVER;
        $this->cle = bin2hex(random_bytes(32));
        $this->router = new Router(new Auth([hash('sha256', $this->cle) => 'test']), new RouteRegistry());
        $this->depot = new DepotSoumissionsFactice();
        Ereferer::utiliserDepot($this->depot);
    }

    protected function tearDown(): void
    {
        Ereferer::utiliserDepot(null);
        $_SERVER = $this->serveurAvant;
    }

    private function poster(array $corps, string $uri = '/api/v1/ereferer'): Response
    {
        return $this->router->traiter(new Requete('POST', $uri, (string) json_encode($corps), 'Bearer ' . $this->cle));
    }

    public function testEmpreintesNormaliseesEnMinuscules(): void
    {
        $factice = strtoupper(hash('sha256', Auth::CLE_FACTICE));
        $empreintes = [$factice => 'x', strtoupper(hash('sha256', $this->cle)) => 'y'];
        $router = new Router(new Auth($empreintes), new RouteRegistry());
        $refus = new Requete('POST', '/api/v1/ereferer', '{}', 'Bearer ' . Auth::CLE_FACTICE);
        $this->assertSame(401, $router->traiter($refus)->statut());
        $accepte = new Requete('POST', '/api/v1/ereferer', '{}', 'Bearer ' . $this->cle);
        $this->assertSame(422, $router->traiter($accepte)->statut());
    }

    public function testDepuisGlobalesLitLEnteteDeCle(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_SERVER['REQUEST_URI'] = '/wp-json/v1/ereferer';
        unset($_SERVER['HTTP_AUTHORIZATION'], $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $_SERVER['CONTENT_LENGTH']);
        $this->assertNull(Requete::depuisGlobales()->autorisation());
        $this->assertSame(401, $this->router->traiter(Requete::depuisGlobales())->statut(), 'méthode en minuscules');

        $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Bearer ' . $this->cle;
        $this->assertSame('POST', Requete::depuisGlobales()->methode());
        $this->assertSame(422, $this->router->traiter(Requete::depuisGlobales())->statut());

        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer mauvaise';
        $this->assertSame('Bearer mauvaise', Requete::depuisGlobales()->autorisation());
        $this->assertSame(401, $this->router->traiter(Requete::depuisGlobales())->statut());
    }

    public function testDepuisGlobalesLitLeFluxApresAuthentification(): void
    {
        $flux = tempnam(sys_get_temp_dir(), 'api');
        file_put_contents($flux, '{"id":5,"post_title":"T","post_content":"C"}');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/api/v1/ereferer';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->cle;
        try {
            $reponse = $this->router->traiter(Requete::depuisGlobales($flux));
        } finally {
            unlink($flux);
        }
        $this->assertSame('publish_pending', $reponse->donnees()['code']);
        $this->assertSame('5', $this->depot->enregistrees[0]['id_externe']);
    }

    public function testCorpsNonLuSansCle(): void
    {
        $lu = false;
        $corps = static function (int $limite) use (&$lu): string {
            $lu = true;

            return '{}';
        };
        $this->assertSame(401, $this->router->traiter(new Requete('POST', '/api/v1/ereferer', $corps))->statut());
        $this->assertFalse($lu);
    }

    public function testCorpsTropVolumineuxDonne413(): void
    {
        $this->assertSame(1048576, API_TAILLE_MAX_CORPS);
        $requete = new Requete('POST', '/api/v1/ereferer', str_repeat(' ', 11), 'Bearer ' . $this->cle, 10);
        $this->assertSame(413, $this->router->traiter($requete)->statut());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/api/v1/ereferer';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->cle;
        $_SERVER['CONTENT_LENGTH'] = (string) (API_TAILLE_MAX_CORPS + 1);
        $reponse = $this->router->traiter(Requete::depuisGlobales('/fichier/inexistant'));
        $this->assertSame(413, $reponse->statut());
        $this->assertSame([], $this->depot->enregistrees);
    }

    public function testJsonTropProfondOuInvalideDonne400(): void
    {
        $profond = str_repeat('{"a":', 40) . '1' . str_repeat('}', 40);
        foreach ([$profond, 'pas du json', '"chaine"'] as $corps) {
            $requete = new Requete('POST', '/api/v1/ereferer', $corps, 'Bearer ' . $this->cle);
            $this->assertSame(400, $this->router->traiter($requete)->statut(), $corps);
        }
    }

    /**
     * @dataProvider horsLimites
     */
    public function testLimitesEreferer(array $ajouts): void
    {
        $base = ['id' => 3, 'post_title' => 'T', 'post_content' => 'C'];
        $reponse = $this->poster($ajouts + $base);
        $this->assertSame(422, $reponse->statut());
        $this->assertSame([], $this->depot->enregistrees);
    }

    public function horsLimites(): array
    {
        $cles = [];
        for ($i = 0; $i < 48; $i++) {
            $cles['x' . $i] = 1;
        }

        return [
            'titre > 255' => [['post_title' => str_repeat('é', 256)]],
            'contenu > 200 000' => [['post_content' => str_repeat('a', 200001)]],
            'plus de 20 catégories' => [['categories' => range(1, 21)]],
            'meta_title > 255' => [['meta_title' => str_repeat('a', 256)]],
            'meta_description > 500' => [['meta_description' => str_repeat('a', 501)]],
            'custom_field > 255' => [['custom_field' => str_repeat('a', 256)]],
            'plus de 50 entrées' => [$cles],
        ];
    }

    public function testLimitesAtteintesAcceptees(): void
    {
        $reponse = $this->poster([
            'id' => 3,
            'post_title' => str_repeat('é', 255),
            'post_content' => str_repeat('a', 200000),
            'categories' => range(1, 20),
            'meta_title' => str_repeat('a', 255),
            'meta_description' => str_repeat('a', 500),
            'custom_field' => str_repeat('a', 255),
        ]);
        $this->assertSame(200, $reponse->statut());
    }

    public function testDeleteEnChaineFalseNeSupprimePas(): void
    {
        $this->poster(['id' => 3, 'delete' => 'false', 'post_title' => 'T', 'post_content' => 'C']);
        $this->assertSame([], $this->depot->supprimees);
        $this->assertCount(1, $this->depot->enregistrees);
    }

    public function testHeadSurWpV2CommeGet(): void
    {
        foreach (['/wp-json/wp/v2/posts', '/wp-json/wp/v2/posts/3'] as $uri) {
            $this->assertSame(404, $this->router->traiter(new Requete('HEAD', $uri))->statut(), $uri);
        }
        $this->assertSame(405, $this->router->traiter(new Requete('HEAD', '/api/v1/ereferer'))->statut());
    }

    public function testCheminsDetournesSansCle(): void
    {
        $corps = '{"id":3,"post_title":"T","post_content":"C"}';
        $double = $this->router->traiter(new Requete('POST', '//wp-json/v1/ereferer', $corps));
        $this->assertSame(401, $double->statut());
        foreach (['/wp-json%2Fv1%2Fereferer', '/WP-JSON/v1/ereferer'] as $uri) {
            $this->assertFalse(Application::estRequeteApi($uri), $uri);
            $this->assertSame(404, $this->router->traiter(new Requete('POST', $uri, $corps))->statut(), $uri);
        }
        $this->assertSame([], $this->depot->enregistrees);
    }

    public function testJournalSansDetailNiCorps(): void
    {
        $this->depot->mode = 'panne';
        $this->poster(['id' => 3, 'post_title' => 'T', 'post_content' => 'CORPS-CONFIDENTIEL']);
        $journal = (string) file_get_contents(LOG_PATH . DIRECTORY_SEPARATOR . 'app.api.log');
        $derniere = substr($journal, (int) strrpos($journal, 'RuntimeException'));
        $this->assertStringStartsWith('RuntimeException : Échec SQL', $derniere);
        $this->assertLessThanOrEqual(240, strlen(trim($derniere)));
        $this->assertStringNotContainsString('DepotSoumissionsFactice.php', $derniere);
        $this->assertStringNotContainsString('CORPS-CONFIDENTIEL', $derniere);
    }

    public function testMaintenance(): void
    {
        $this->assertTrue(API_PENDANT_MAINTENANCE);
        $this->assertFalse(Application::estEnMaintenance());
        $this->assertFalse(Application::apiFermee(true, true));
        $this->assertTrue(Application::apiFermee(false, true));
        $this->assertFalse(Application::apiFermee(false, false));
    }
}
