<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Niveau d'erreurs de Dumper : E_ALL, dépréciations et notices comprises,
 * utiles à la migration vers PHP 8.3. Chaque appel modifie des réglages
 * ini du processus : il est joué dans un processus PHP séparé.
 */
final class DumperTest extends TestCase
{
    /**
     * @dataProvider methodes
     */
    public function testNiveauEAll(string $methode): void
    {
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ' require ' . var_export($autoload, true) . ';'
            . ' error_reporting(0); \MVC\Classe\Dumper::' . $methode . '();'
            . ' echo error_reporting() === E_ALL ? "E_ALL" : error_reporting();';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);

        $this->assertSame(0, $retour, implode("\n", $sortie));
        $this->assertSame('E_ALL', trim(implode("\n", $sortie)));
    }

    public function methodes(): array
    {
        return [
            'setPHPvalues (public/index.php)' => ['setPHPvalues'],
            'display_errors (aide au débogage)' => ['display_errors'],
        ];
    }
}
