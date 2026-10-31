<?php

declare(strict_types=1);

namespace Tests;

use MVC\Api\Component\Auth;
use MVC\Api\Component\FabriqueDepot;
use MVC\Api\Component\Requete;
use MVC\Api\Component\Response;
use MVC\Api\Component\RouteRegistry;
use MVC\Api\Component\Router;
use MVC\Api\Controller\v1\Ereferer;
use PHPUnit\Framework\TestCase;

/**
 * Route Ereferer : authentification, dépôt absent, validation, succès, erreurs.
 * La clé d'API est générée à chaque test : aucune clé n'est écrite sur disque.
 */
final class ApiErefererTest extends TestCase
{
    private string $cle;
    private Router $router;
    private DepotSoumissionsFactice $depot;

    protected function setUp(): void
    {
        $this->cle = bin2hex(random_bytes(32));
        $this->router = new Router(new Auth([hash('sha256', $this->cle) => 'ereferer']), new RouteRegistry());
        $this->depot = new DepotSoumissionsFactice();
        Ereferer::utiliserDepot($this->depot);
    }

    protected function tearDown(): void
    {
        Ereferer::utiliserDepot(null);
    }

    private function poster(array $corps, ?string $cle = null, string $uri = '/wp-json/v1/ereferer'): Response
    {
        $autorisation = $cle === null ? null : 'Bearer ' . $cle;
        $requete = new Requete('POST', $uri, (string) json_encode($corps), $autorisation);

        return $this->router->traiter($requete);
    }

    private function soumission(array $ajouts = []): array
    {
        return $ajouts + [
            'id' => 42,
            'post_title' => 'Titre%e2%80%89 de test',
            'post_content' => '<p>Contenu</p>',
            'categories' => ['culture', 'sport'],
            'front_image' => 'https://exemple.test/image.jpg',
            'meta_title' => 'Méta titre',
        ];
    }

    public function testSansCleOuAvecMauvaiseCleDonneLeMeme401(): void
    {
        $sansCle = $this->poster($this->soumission());
        $mauvaiseCle = $this->poster($this->soumission(), bin2hex(random_bytes(32)));
        $schemaInvalide = $this->router->traiter(
            new Requete('POST', '/api/v1/ereferer', '{}', 'Basic ' . $this->cle)
        );

        foreach ([$sansCle, $mauvaiseCle, $schemaInvalide] as $reponse) {
            $this->assertSame(401, $reponse->statut());
            $this->assertSame($sansCle->donnees(), $reponse->donnees());
            $this->assertSame('Bearer', $reponse->entetes()['WWW-Authenticate']);
        }
        $this->assertSame([], $this->depot->enregistrees);
    }

    public function testCleFacticeDuModeleJamaisAcceptee(): void
    {
        $router = new Router(new Auth([hash('sha256', Auth::CLE_FACTICE) => 'ereferer']), new RouteRegistry());
        $requete = new Requete('POST', '/api/v1/ereferer', '{}', 'Bearer ' . Auth::CLE_FACTICE);
        $this->assertSame(401, $router->traiter($requete)->statut());
    }

    public function testSansDepotRepond501(): void
    {
        Ereferer::utiliserDepot(null);
        $this->assertNull(API_DEPOT_SOUMISSIONS);
        foreach (['/wp-json/v1/ereferer', '/api/v1/ereferer'] as $uri) {
            $reponse = $this->poster($this->soumission(), $this->cle, $uri);
            $this->assertSame(501, $reponse->statut(), $uri);
            $this->assertFalse($reponse->donnees()['status']);
        }
    }

    /**
     * @dataProvider champsInvalides
     */
    public function testChampsInvalidesDonnent422(array $corps, string $code): void
    {
        $reponse = $this->poster($corps, $this->cle);
        $this->assertSame(422, $reponse->statut());
        $this->assertSame($code, $reponse->donnees()['code']);
        $this->assertSame([], $this->depot->enregistrees);
    }

    public function champsInvalides(): array
    {
        $base = ['id' => 3, 'post_title' => 'T', 'post_content' => 'C'];

        return [
            'corps vide' => [[], 'incorrect_post_id'],
            'id non numérique' => [['id' => 'abc'] + $base, 'incorrect_post_id'],
            'titre manquant' => [['id' => 3, 'post_content' => 'C'], 'incorrect_post'],
            'contenu vide' => [['post_content' => ' '] + $base, 'incorrect_post'],
            'catégories non tableau' => [$base + ['categories' => 'x'], 'incorrect_field'],
            'image non URL' => [$base + ['front_image' => 'javascript:x'], 'incorrect_field'],
            'méta non chaîne' => [$base + ['meta_title' => ['x']], 'incorrect_field'],
        ];
    }

    public function testEnregistrementEnAttente(): void
    {
        $reponse = $this->poster($this->soumission(), $this->cle);
        $this->assertSame(200, $reponse->statut());
        $this->assertSame([
            'status' => true,
            'code' => 'publish_pending',
            'message' => 'Success',
            'publish_status' => 'pending',
            'article_url' => null,
            'identifiant' => 'B-42',
        ], $reponse->donnees());
        $this->assertSame([[
            'id_externe' => '42',
            'titre' => 'Titre de test',
            'contenu' => '<p>Contenu</p>',
            'categories' => ['culture', 'sport'],
            'image' => 'https://exemple.test/image.jpg',
            'meta_titre' => 'Méta titre',
            'meta_description' => null,
            'source_image' => null,
            'forcer' => false,
        ]], $this->depot->enregistrees);
    }

    public function testEnregistrementPublie(): void
    {
        $this->depot->mode = 'publie';
        $donnees = $this->poster($this->soumission(['force' => true]), $this->cle)->donnees();
        $this->assertSame('publish_success', $donnees['code']);
        $this->assertSame('https://exemple.test/article/42', $donnees['article_url']);
        $this->assertTrue($this->depot->enregistrees[0]['forcer']);
    }

    public function testSoumissionNonModifiableDonne409(): void
    {
        $this->depot->mode = 'refus';
        $reponse = $this->poster($this->soumission(), $this->cle);
        $this->assertSame(409, $reponse->statut());
        $this->assertSame('post_not_editable', $reponse->donnees()['code']);
    }

    public function testPanneDuDepotDonne500SansDetail(): void
    {
        $this->depot->mode = 'panne';
        $reponse = $this->poster($this->soumission(), $this->cle);
        $this->assertSame(500, $reponse->statut());
        $this->assertSame('Erreur interne', $reponse->donnees()['message']);
        $this->assertStringNotContainsString('chemin', (string) json_encode($reponse->donnees()));
    }

    public function testTestDeConnexionEtSuppression(): void
    {
        $connexion = $this->poster(['test_connection' => true], $this->cle)->donnees();
        $this->assertSame('success_connection', $connexion['code']);

        $suppression = $this->poster(['id' => 7, 'delete' => true], $this->cle);
        $this->assertSame('success_delete', $suppression->donnees()['code']);
        $echec = $this->poster(['id' => 404, 'delete' => true], $this->cle);
        $this->assertSame(404, $echec->statut());
        $this->assertSame('failed_delete', $echec->donnees()['code']);
        $this->assertSame(['7', '404'], $this->depot->supprimees);
    }

    public function testAutresVerbesDonnent405(): void
    {
        foreach (['PUT', 'PATCH', 'DELETE'] as $methode) {
            $requete = new Requete($methode, '/wp-json/v1/ereferer/3', '{}', 'Bearer ' . $this->cle);
            $this->assertSame(405, $this->router->traiter($requete)->statut(), $methode);
        }
    }

    public function testFabriqueDuDepot(): void
    {
        $this->assertNull(FabriqueDepot::creer(null));
        $this->assertNull(FabriqueDepot::creer(''));
        $this->assertInstanceOf(DepotSoumissionsFactice::class, FabriqueDepot::creer(DepotSoumissionsFactice::class));

        foreach (['Tests\\ClasseInexistante', \stdClass::class, 42] as $invalide) {
            try {
                FabriqueDepot::creer($invalide);
                $this->fail('Classe acceptée à tort : ' . var_export($invalide, true));
            } catch (\LogicException $e) {
                $this->assertStringContainsString('API_DEPOT_SOUMISSIONS', $e->getMessage());
            }
        }
    }
}
