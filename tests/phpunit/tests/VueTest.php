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

    /**
     * Construit la Vue de la page de test « page-vue » avec le moteur donné.
     */
    private function vue(string $moteur): Vue
    {
        $parametres = [
            'name' => 'page-vue',
            'engine' => $moteur,
            'page_title' => 'Titre ' . $moteur,
            'all_params' => ['cle' => 'valeur'],
        ];

        $controleurs = $this->gabarits . DIRECTORY_SEPARATOR . 'controlleurs';

        return new Vue($parametres, $this->gabarits, $controleurs, $this->cache);
    }
}
