<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Bout en bout par HTTP réel : php -S sur public/index.php (Application, puis
 * Requete::depuisGlobales() et Router::depuisConfiguration()), avec un fichier
 * de clés généré dans un dossier temporaire. Un serveur par test, arrêté dans tearDown().
 */
final class ApiHttpTest extends TestCase
{
    private ?ServeurPhpIntegre $serveur = null;
    private string $cle;

    protected function setUp(): void
    {
        $this->cle = bin2hex(random_bytes(32));
    }

    protected function tearDown(): void
    {
        $this->serveur?->arreter();
        $this->serveur = null;
    }

    /**
     * Le fichier de clés est écrit par ServeurPhpIntegre dans son dossier temporaire
     * (constante API_FICHIER_CLES), jamais dans Api/config/.
     */
    private function demarrerAvecCles(array $ajouts = []): ServeurPhpIntegre
    {
        $constantes = $ajouts + [
            'API_DEPOT_SOUMISSIONS' => 'Tests\\DepotSoumissionsFactice',
            'API_TAILLE_MAX_CORPS' => 2000,
        ];
        $cles = [hash('sha256', $this->cle) => 'ereferer'];
        $this->serveur = new ServeurPhpIntegre(dirname(__DIR__, 3), $constantes, $cles);

        return $this->serveur;
    }

    private function soumission(): string
    {
        return (string) json_encode(['id' => 9, 'post_title' => 'Titre', 'post_content' => '<p>Texte</p>']);
    }

    public function testEcritureSansCleOuMauvaiseCle401SansControlleur(): void
    {
        $serveur = $this->demarrerAvecCles();
        $entetes = [[], ['Authorization' => 'Bearer ' . bin2hex(random_bytes(32))], ['Authorization' => 'Basic x']];
        foreach ($entetes as $entete) {
            $reponse = $serveur->requete('POST', '/wp-json/v1/ereferer', $entete, $this->soumission());
            $this->assertSame(401, $reponse['statut']);
            $this->assertStringContainsString('application/json', $reponse['entetes']);
            $this->assertSame('Authentification requise', json_decode($reponse['corps'], true)['message']);
            $this->assertSame('non', $serveur->lire('controlleur.txt'));
        }
        $this->assertSame('', $serveur->lire('depot.log'));
    }

    public function testCleValideParLEnteteAuthorization(): void
    {
        $serveur = $this->demarrerAvecCles();
        $cible = ['/wp-json/v1/ereferer', '/api/v1/ereferer', '//wp-json/v1/ereferer'];
        foreach ($cible as $uri) {
            $entetes = ['Authorization' => 'Bearer ' . $this->cle];
            $reponse = $serveur->requete('POST', $uri, $entetes, $this->soumission());
            $this->assertSame(200, $reponse['statut'], $uri . ' ' . $reponse['corps']);
            $this->assertSame('publish_pending', json_decode($reponse['corps'], true)['code']);
        }
        $this->assertSame(str_repeat('enregistrer 9' . PHP_EOL, 3), $serveur->lire('depot.log'));
        $this->assertSame('non', $serveur->lire('controlleur.txt'));
    }

    public function testMethodesEtCheminsDetournesSansCle(): void
    {
        $serveur = $this->demarrerAvecCles();
        // statut attendu ; null : chemin hors de l'API (%2F non décodé, casse exacte),
        // servi par le MVC comme n'importe quelle page inconnue, ou méthode « post »
        // refusée par l'analyseur HTTP de php -S (cas couvert par ApiDurcissementTest)
        $essais = [
            ['post', '/wp-json/v1/ereferer', null],
            ['HEAD', '/wp-json/v1/ereferer', 405],
            ['POST', '//wp-json/v1/ereferer', 401],
            ['PUT', '/api/v1/ereferer/9', 401],
            ['POST', '/wp-json%2Fv1%2Fereferer', null],
            ['POST', '/WP-JSON/v1/ereferer', null],
        ];
        foreach ($essais as [$methode, $uri, $attendu]) {
            $reponse = $serveur->requete($methode, $uri, [], $this->soumission());
            if ($attendu !== null) {
                $this->assertSame($attendu, $reponse['statut'], $methode . ' ' . $uri);
            }
            $this->assertStringNotContainsString('publish_', $reponse['corps'], $methode . ' ' . $uri);
        }
        $this->assertSame('', $serveur->lire('depot.log'));
    }

    public function testHeadEtGetSurWpV2(): void
    {
        $serveur = $this->demarrerAvecCles();
        $get = $serveur->requete('GET', '/wp-json/wp/v2/posts');
        $head = $serveur->requete('HEAD', '/wp-json/wp/v2/posts');
        $this->assertSame(404, $get['statut']);
        $this->assertSame(404, $head['statut']);
        $this->assertStringContainsString('Posts introuvables', $get['corps']);
        $this->assertSame('', $head['corps']);
        $this->assertSame('non', $serveur->lire('controlleur.txt'));
    }

    public function testCorpsTropVolumineux413(): void
    {
        $serveur = $this->demarrerAvecCles();
        $corps = (string) json_encode(['id' => 9, 'post_title' => 'T', 'post_content' => str_repeat('a', 3000)]);
        $reponse = $serveur->requete('POST', '/api/v1/ereferer', ['Authorization' => 'Bearer ' . $this->cle], $corps);
        $this->assertSame(413, $reponse['statut']);
        $this->assertSame('', $serveur->lire('depot.log'));
    }

    public function testMaintenance(): void
    {
        // APP_STATE défini avant app-parameters.php (sa redéfinition y est ignorée)
        $serveur = $this->demarrerAvecCles(['APP_STATE' => 'MAINTAINED', 'API_PENDANT_MAINTENANCE' => false]);
        $reponse = $serveur->requete('GET', '/wp-json/wp/v2/posts');
        $this->assertSame(503, $reponse['statut']);
        $this->assertTrue(json_decode($reponse['corps'], true)['error']);
        $this->tearDown();

        $serveur = $this->demarrerAvecCles(['APP_STATE' => 'MAINTAINED']);
        $this->assertSame(404, $serveur->requete('GET', '/wp-json/wp/v2/posts')['statut']);
    }

    public function testPageMvcToujoursServie(): void
    {
        $serveur = $this->demarrerAvecCles();
        $reponse = $serveur->requete('GET', '/index');
        $this->assertSame(200, $reponse['statut']);
        $this->assertSame('oui', $serveur->lire('controlleur.txt'));
    }
}
