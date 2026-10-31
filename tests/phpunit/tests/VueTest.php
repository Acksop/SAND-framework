<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Vue;
use PHPUnit\Framework\TestCase;

/**
 * Rendu d'une page complète par MVC\Classe\Vue (contrôleur de page puis gabarit),
 * avec les dossiers de test : les vraies vues de l'application ne sont pas lues.
 */
final class VueTest extends TestCase
{
    /** @var string */
    private $gabarits;
    /** @var string */
    private $cache;
    /** @var array */
    private $getAvant;

    protected function setUp(): void
    {
        $this->gabarits = __DIR__ . DIRECTORY_SEPARATOR . 'gabarits';
        $this->cache = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-vue-' . uniqid();
        $this->getAvant = $_GET;
        Vue::reinitialiserPage();
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
        Vue::reinitialiserPage();
        foreach (glob($this->cache . DIRECTORY_SEPARATOR . '*') ?: [] as $fichier) {
            unlink($fichier);
        }
        if (is_dir($this->cache)) {
            rmdir($this->cache);
        }
    }

    public function testVueRendUnePageBlade(): void
    {
        $vue = $this->vue('blade');

        $this->assertMatchesRegularExpression(
            '#<html><body>\s*<main><h1>Vue blade valeur</h1><p>Titre blade</p>\s*</main>#',
            $vue->ecran
        );
        $this->assertSame('valeur', $_GET['cle']);
        $this->assertNotEmpty(glob($this->cache . DIRECTORY_SEPARATOR . '*.php'), 'Cache Blade vide.');
    }

    public function testVueRendUnePageTwig(): void
    {
        $vue = $this->vue('twig');

        $this->assertMatchesRegularExpression(
            '#<html><body>\s*<main><h1>Vue twig valeur</h1><p>Titre twig</p></main>#',
            $vue->ecran
        );
    }

    public function testLaPremiereVueRenduePorteLeTitreEtLaDescriptionDeLaPage(): void
    {
        $this->assertNull(Vue::titrePage());
        $this->assertNull(Vue::descriptionPage());

        $this->vue('blade', ['description' => 'Description blade']);
        $this->vue('twig', ['description' => 'Description twig']);

        $this->assertSame('Titre blade', Vue::titrePage());
        $this->assertSame('Description blade', Vue::descriptionPage());
    }

    public function testSansDescriptionLeTitreEstExposeEtLaDescriptionVautNull(): void
    {
        $this->vue('twig');

        $this->assertSame('Titre twig', Vue::titrePage());
        $this->assertNull(Vue::descriptionPage());
    }

    public function testUneVueSansTitreNeFigePasLaPage(): void
    {
        $this->vue('blade', ['page_title' => null]);
        $this->assertNull(Vue::titrePage());

        $this->vue('twig');
        $this->assertSame('Titre twig', Vue::titrePage());

        Vue::reinitialiserPage();
        $this->assertNull(Vue::titrePage());
        $this->assertNull(Vue::descriptionPage());
    }

    /**
     * Construit la Vue de la page de test « page-vue » avec le moteur donné.
     */
    private function vue(string $moteur, array $autres = []): Vue
    {
        $parametres = array_merge([
            'name' => 'page-vue',
            'engine' => $moteur,
            'page_title' => 'Titre ' . $moteur,
            'all_params' => ['cle' => 'valeur'],
        ], $autres);

        $controleurs = $this->gabarits . DIRECTORY_SEPARATOR . 'controlleurs';

        return new Vue($parametres, $this->gabarits, $controleurs, $this->cache);
    }
}
