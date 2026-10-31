<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Bdd;
use MVC\Classe\Caracter;
use PHPUnit\Framework\TestCase;

defined('DSN_BDD_TESTSQLITE') or define('DSN_BDD_TESTSQLITE', 'sqlite::memory:');
defined('USER_BDD_TESTSQLITE') or define('USER_BDD_TESTSQLITE', 'utilisateur-secret-test');
defined('PASS_BDD_TESTSQLITE') or define('PASS_BDD_TESTSQLITE', 'mot-de-passe-secret-test');

/**
 * Normalisation UTF-8 de Caracter (mbstring, sans utf8_decode() déprécié
 * depuis PHP 8.2) : même résultat que neitanod/forceutf8 pour l'UTF-8,
 * l'ISO-8859-1 et le double encodage.
 */
final class CaracterTest extends TestCase
{
    /**
     * @dataProvider chaines
     */
    public function testNormalisation(string $entree, string $attendu): void
    {
        $erreurs = $this->erreursDe(static function () use ($entree, &$resultat): void {
            $resultat = Caracter::normalise_ChaineDeCaracteres($entree);
        });

        $this->assertSame([], $erreurs);
        $this->assertSame($attendu, $resultat);
    }

    public function chaines(): array
    {
        $iso = static fn (string $texte): string => mb_convert_encoding($texte, 'ISO-8859-1', 'UTF-8');
        $double = static fn (string $texte): string => mb_convert_encoding($texte, 'UTF-8', 'ISO-8859-1');

        return [
            'ASCII' => ['Bonjour le monde', 'Bonjour le monde'],
            'UTF-8 Latin-1' => ["L'été à Besançon, 25°C ±2", "L'été à Besançon, 25°C ±2"],
            'UTF-8 hors Latin-1 conservé' => ['œuvre, 5 € — 日本語 😀', 'œuvre, 5 € — 日本語 😀'],
            'ISO-8859-1' => [$iso('Été à Besançon'), 'Été à Besançon'],
            'Windows-1252' => [mb_convert_encoding('œuvre 5 €', 'Windows-1252', 'UTF-8'), 'œuvre 5 €'],
            'ISO-8859-1 et UTF-8 mêlés' => [$iso('été') . ' Été', 'été Été'],
            'double encodage' => [$double('Été à Besançon'), 'Été à Besançon'],
            'triple encodage' => [$double($double('Été')), 'Été'],
            'double encodage Windows-1252' => [
                mb_convert_encoding('œuvre € —', 'UTF-8', 'Windows-1252'),
                'œuvre € —',
            ],
            'UTF-8 et double encodage mêlés' => ['Été ' . $double('Été'), 'Été Été'],
            'vide' => ['', ''],
        ];
    }

    public function testValeursNonChainesInchangees(): void
    {
        $this->assertSame(42, Caracter::corrigerUtf8(42));
        $this->assertNull(Caracter::corrigerUtf8(null));
        $this->assertSame(
            ['a' => 'Été', 'b' => [7, 'Été']],
            Caracter::corrigerUtf8(['a' => mb_convert_encoding('Été', 'ISO-8859-1', 'UTF-8'), 'b' => [7, 'Été']])
        );
    }

    public function testUploadEchappeApresCorrection(): void
    {
        $this->assertSame("l\\'été", Caracter::normalise_ChaineDeCaracteresUpload(
            mb_convert_encoding("l'été", 'ISO-8859-1', 'UTF-8')
        ));
    }

    public function testFaireBindRequeteAvecParametresSansDepreciation(): void
    {
        $bdd = new Bdd('testsqlite');
        $bdd->bdd->exec('CREATE TABLE IF NOT EXISTS accents (id INTEGER, v TEXT)');
        $bdd->bdd->exec('DELETE FROM accents');

        $erreurs = $this->erreursDe(static function () use ($bdd, &$req): void {
            $req = $bdd->faireBindRequete(
                'INSERT INTO accents (id, v) VALUES (:id, :v)',
                [['id', 1, \PDO::PARAM_INT], ['v', mb_convert_encoding('Été', 'ISO-8859-1', 'UTF-8'), 'VALUE']]
            );
        });

        $this->assertSame([], $erreurs);
        $this->assertNotSame(0, $req);
        $this->assertSame('Été', $bdd->bdd->query('SELECT v FROM accents WHERE id = 1')->fetchColumn());
    }

    /**
     * Exécute $code et renvoie toutes les erreurs PHP émises (dépréciations comprises).
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
