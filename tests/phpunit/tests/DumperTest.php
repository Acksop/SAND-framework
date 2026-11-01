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
            . ' error_reporting(0); \SAND\Classe\Dumper::' . $methode . '();'
            . ' echo error_reporting() === E_ALL ? "E_ALL" : error_reporting();';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);

        $this->assertSame(0, $retour, implode("\n", $sortie));
        $this->assertSame('E_ALL', trim(implode("\n", $sortie)));
    }

    /**
     * Hors débogage, setPHPvalues() n'affiche pas les erreurs (chemins du
     * serveur) mais les journalise ; en débogage, elle les affiche.
     *
     * @dataProvider modesDebug
     */
    public function testAffichageDesErreursSelonDebug(?string $definition, string $attendu): void
    {
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ($definition === null ? '' : ' define("DEBUG", ' . $definition . ');')
            . ' require ' . var_export($autoload, true) . ';'
            . ' ini_set("display_errors", "1"); ini_set("log_errors", "0"); error_reporting(0);'
            . ' \SAND\Classe\Dumper::setPHPvalues();'
            . ' echo json_encode([(bool) ini_get("display_errors"), (bool) ini_get("log_errors"),'
            . ' error_reporting() === E_ALL]);';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);

        $this->assertSame(0, $retour, implode("\n", $sortie));
        $this->assertSame($attendu, trim(implode("\n", $sortie)));
    }

    public function modesDebug(): array
    {
        return [
            'DEBUG true : affichage' => ['true', '[true,false,true]'],
            'DEBUG false : journal seulement' => ['false', '[false,true,true]'],
            'DEBUG non défini : journal seulement' => [null, '[false,true,true]'],
        ];
    }

    public function methodes(): array
    {
        return [
            'setPHPvalues (public/index.php)' => ['setPHPvalues'],
            'display_errors (aide au débogage)' => ['display_errors'],
        ];
    }
}
