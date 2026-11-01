<?php

declare(strict_types=1);

namespace Tests;

use SAND\Classe\Logger;
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
        $fichier = $this->fichierJournal($type);

        Logger::addLog($type, 'message');

        $contenu = (string) file_get_contents($fichier);
        $this->assertStringContainsString('Agent/1.0 (essai)', $contenu);
    }

    /**
     * DEBUG est une constante : chaque mode est joué dans un processus PHP
     * qui la définit avant l'autoload, quelle que soit la valeur du processus
     * de test. La sortie est ce que la requête a ajouté au journal.
     *
     * @dataProvider modesDebug
     */
    public function testJournalHttpMethodSelonDebug(string $debug, string $methode, bool $journalise): void
    {
        $this->fichierJournal('http.method');
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ' $_SERVER["REQUEST_METHOD"] = ' . var_export($methode, true) . '; define("DEBUG", ' . $debug . ');'
            . ' require ' . var_export($autoload, true) . ';'
            . ' $f = LOG_PATH . DIRECTORY_SEPARATOR . "app.http.method.log";'
            . ' $avant = is_file($f) ? filesize($f) : 0;'
            . ' $http = new \SAND\Classe\HttpMethod(); echo $http->method, "|";'
            . ' clearstatcache(); echo is_file($f) ? substr((string) file_get_contents($f), $avant) : "";';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);
        $texte = implode("\n", $sortie);

        $this->assertSame(0, $retour, $texte);
        $this->assertStringStartsWith($methode . '|', $texte);
        $this->assertStringNotContainsString('Warning', $texte);
        if ($journalise) {
            $this->assertStringContainsString($methode, substr($texte, strlen($methode) + 1));
        } else {
            $this->assertSame($methode . '|', $texte);
        }
    }

    public function modesDebug(): array
    {
        return [
            'débogage : journalisé' => ['true', 'DELETE', true],
            'hors débogage : rien' => ['false', 'GET', false],
        ];
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
