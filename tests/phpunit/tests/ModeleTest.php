<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Modele;
use PHPUnit\Framework\TestCase;

/**
 * Lecture des fichiers .model : clés et valeurs sans espaces parasites.
 * Les .model de test sont écrits dans un dossier temporaire, passé au
 * constructeur à la place de MODELS_PATH.
 */
final class ModeleTest extends TestCase
{
    private string $dossier;
    private array $getAvant;

    protected function setUp(): void
    {
        $this->dossier = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-modele-' . bin2hex(random_bytes(4));
        mkdir($this->dossier);
        $this->getAvant = $_GET;
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dossier . DIRECTORY_SEPARATOR . '*') ?: [] as $fichier) {
            unlink($fichier);
        }
        rmdir($this->dossier);
        $_GET = $this->getAvant;
    }

    public function testClesEtValeursSansEspacesParasites(): void
    {
        $page = $this->lire("name : essai   \npage_title:  Titre de la page  \r\n  engine :  twig\t\n");

        $this->assertSame('essai', $page['name']);
        $this->assertSame('Titre de la page', $page['page_title']);
        $this->assertSame('twig', $page['engine']);
    }

    public function testElementsDeTableauSansEspacesParasites(): void
    {
        $page = $this->lire("ariane : {  accueil ,  page }\narianelink : {index,page}\n");

        $this->assertSame(['accueil', 'page'], $page['ariane']);
        $this->assertSame(['index', 'page'], $page['arianelink']);
    }

    public function testParametresExportesDansGet(): void
    {
        $page = $this->lire("name : essai\n", ['id' => '4']);

        $this->assertSame(['id' => '4'], $page['all_params']);
        $this->assertSame('4', $_GET['id']);
    }

    /**
     * @dataProvider valeursAvecSousTableaux
     */
    public function testSousTableauxDansUneValeur(string $valeur, array $attendu): void
    {
        $this->assertSame($attendu, Modele::analyserValeur($valeur));
    }

    public function valeursAvecSousTableaux(): array
    {
        return [
            'vide puis associatif' => ['{[], [id=4]}', [[], ['id' => '4']]],
            'liste' => ['{[a, b]}', [['a', 'b']]],
            'un seul vide' => ['{[]}', [[]]],
            'deux vides' => ['{[], []}', [[], []]],
            'mélange simple et associatif' => ['{x, [k=v]}', ['x', ['k' => 'v']]],
            'espaces parasites' => [
                '{  [ id = 4 ,  nom= dupont ] ,   page  , [  ] }',
                [['id' => '4', 'nom' => 'dupont'], 'page', []],
            ],
            'accents' => ['{Été, [titre=Château à Besançon, ville=Besançon]}', [
                'Été',
                ['titre' => 'Château à Besançon', 'ville' => 'Besançon'],
            ]],
            'éléments simples inchangés' => ['{ accueil , page }', ['accueil', 'page']],
            'imbrication hors périmètre : texte brut' => ['{[[a]]}', [['[a]']]],
        ];
    }

    public function testValeurSimpleInchangee(): void
    {
        $this->assertSame('Titre', Modele::analyserValeur('  Titre '));
    }

    public function testArianeParamsDepuisUnFichierEtLienReecrit(): void
    {
        $page = $this->lire("ariane : {accueil, page}\narianelink : {index, page}\narianeparams : {[], [id=4]}\n");

        $this->assertSame([[], ['id' => '4']], $page['arianeparams']);
        foreach ([0 => 'index', 1 => 'page/id/4'] as $i => $chemin) {
            $this->assertSame(
                '/' . BASE_SERVER_DIRECTORY . $chemin,
                \MVC\Classe\Url::link_rewrite(false, $page['arianelink'][$i], $page['arianeparams'][$i])
            );
        }
    }

    private function lire(string $contenu, array $params = []): array
    {
        file_put_contents($this->dossier . DIRECTORY_SEPARATOR . 'essai.model', $contenu);

        return (new Modele(['name' => 'essai', 'params' => $params], $this->dossier))->page;
    }
}
