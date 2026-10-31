<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\HttpMethod;
use MVC\Classe\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Logger::addLog() en ligne de commande (sans User-Agent) et journal
 * http.method écrit seulement en mode débogage.
 */
final class JournalTest extends TestCase
{
    private array $serveurAvant;
    private array $fichiers = [];

    protected function setUp(): void
    {
        $this->serveurAvant = $_SERVER;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serveurAvant;
        foreach ($this->fichiers as $fichier) {
            if (is_file($fichier)) {
                unlink($fichier);
            }
        }
    }

    public function testAddLogSansUserAgentNEmetAucunAvertissement(): void
    {
        unset($_SERVER['HTTP_USER_AGENT']);
        $type = 'test-cli-' . bin2hex(random_bytes(4));
        $fichier = $this->fichierJournal($type);

        $erreurs = $this->erreursDe(static function () use ($type): void {
            Logger::addLog($type, 'message de test');
        });

        $this->assertSame([], $erreurs);
        $this->assertFileExists($fichier);
        $contenu = (string) file_get_contents($fichier);
        $this->assertStringContainsString('inconnu', $contenu);
        $this->assertStringContainsString('message de test', $contenu);
    }

    public function testAddLogGardeLeUserAgentBrut(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Agent/1.0 (essai)';
        $type = 'test-ua-' . bin2hex(random_bytes(4));

        Logger::addLog($type, 'message');

        $this->assertStringContainsString('Agent/1.0 (essai)', (string) file_get_contents($this->fichierJournal($type)));
    }

    public function testHttpMethodNeJournaliseRienHorsDebogage(): void
    {
        $this->assertFalse(\MVC\Object\Environment::debug());
        $fichier = $this->fichierJournal('http.method');
        $avant = is_file($fichier) ? (string) file_get_contents($fichier) : null;
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $methode = new HttpMethod();

        $this->assertSame('GET', $methode->method);
        $this->assertSame($avant, is_file($fichier) ? (string) file_get_contents($fichier) : null);
    }

    public function testHttpMethodJournaliseEnDebogage(): void
    {
        $this->fichierJournal('http.method');
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ' $_SERVER["REQUEST_METHOD"] = "DELETE"; define("DEBUG", true);'
            . ' require ' . var_export($autoload, true) . ';'
            . ' $f = LOG_PATH . DIRECTORY_SEPARATOR . "app.http.method.log";'
            . ' $avant = is_file($f) ? filesize($f) : 0;'
            . ' new \MVC\Classe\HttpMethod();'
            . ' clearstatcache(); echo substr((string) file_get_contents($f), $avant);';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);

        $this->assertSame(0, $retour, implode("\n", $sortie));
        $this->assertStringContainsString('DELETE', implode("\n", $sortie));
        $this->assertStringNotContainsString('Warning', implode("\n", $sortie));
    }

    /**
     * Chemin du journal ; il sera supprimé après le test s'il n'existait pas avant.
     */
    private function fichierJournal(string $type): string
    {
        $fichier = LOG_PATH . DIRECTORY_SEPARATOR . 'app.' . $type . '.log';
        if (!is_file($fichier)) {
            $this->fichiers[] = $fichier;
        }

        return $fichier;
    }

    /**
     * Exécute $code et renvoie toutes les erreurs PHP émises.
     */
    private function erreursDe(callable $code): array
    {
        $erreurs = [];
        set_error_handler(static function (int $niveau, string $message) use (&$erreurs): bool {
            $erreurs[] = $message;

            return true;
        });

        try {
            $code();
        } finally {
            restore_error_handler();
        }

        return $erreurs;
    }
}
