<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Exemple de test sur un cas simple
 * @package Tests
 */
final class UrlTest extends TestCase
{
    public function testCompareCharge(): void
    {
        $baseDirectory = \SAND\Classe\Url::getBaseDirectory();
        if('/' . BASE_SERVER_DIRECTORY == $baseDirectory) {
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }

    public function testUriSansCheminOuAvecHoteNeLeveNiAvertissementNiDeprecation(): void
    {
        $avant = $_SERVER['REQUEST_URI'] ?? null;
        $erreurs = [];
        set_error_handler(static function (int $niveau, string $message) use (&$erreurs): bool {
            $erreurs[] = $niveau . ' ' . $message;
            return true;
        });

        try {
            foreach (['//hote/x', '//hote'] as $uri) {
                $_SERVER['REQUEST_URI'] = $uri;
                \SAND\Classe\Url::getPageName();
            }
        } finally {
            restore_error_handler();
            if ($avant === null) {
                unset($_SERVER['REQUEST_URI']);
            } else {
                $_SERVER['REQUEST_URI'] = $avant;
            }
        }

        $this->assertSame([], $erreurs);
    }
}
