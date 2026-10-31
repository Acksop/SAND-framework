<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Bdd;
use PHPUnit\Framework\TestCase;

// Connexions de test : SQLite en mémoire, aucune base réelle n'est touchée.
// Une connexion SQLite ignore l'utilisateur et le mot de passe : les valeurs
// ci-dessous servent à vérifier qu'ils n'apparaissent jamais dans le journal.
defined('DSN_BDD_TESTSQLITE') or define('DSN_BDD_TESTSQLITE', 'sqlite::memory:');
defined('USER_BDD_TESTSQLITE') or define('USER_BDD_TESTSQLITE', 'utilisateur-secret-test');
defined('PASS_BDD_TESTSQLITE') or define('PASS_BDD_TESTSQLITE', 'mot-de-passe-secret-test');
// Forme historique (sans le préfixe BDD_), comme DSN_BDD1 et DSN_BDD2
defined('DSN_HISTORIQUETEST') or define('DSN_HISTORIQUETEST', 'sqlite::memory:');
defined('USER_HISTORIQUETEST') or define('USER_HISTORIQUETEST', 'u-historique');
defined('PASS_HISTORIQUETEST') or define('PASS_HISTORIQUETEST', 'p-historique');
// Les deux formes : DSN_BDD_<NOM> l'emporte
defined('DSN_BDD_PRIORITETEST') or define('DSN_BDD_PRIORITETEST', 'sqlite::memory:');
defined('DSN_PRIORITETEST') or define('DSN_PRIORITETEST', 'sqlite:/chemin/qui/ne/doit/pas/servir');
// Utilisateur et mot de passe facultatifs
defined('DSN_BDD_SANSUTILISATEUR') or define('DSN_BDD_SANSUTILISATEUR', 'sqlite::memory:');

/**
 * Connexions nommées, Bdd::pourPage(), erreurs de faireBindRequete() et
 * exploiterNbResultat().
 */
final class BddTest extends TestCase
{
    public function testDefaultEtBdd1Bdd2RestentCompatibles(): void
    {
        $this->assertSame([DSN_BDD_DEFAULT, USER_BDD_DEFAULT, PASS_BDD_DEFAULT], Bdd::identifiants('default'));
        $this->assertSame([DSN_BDD1, USER_BDD1, PASS_BDD1], Bdd::identifiants('bdd1'));
        $this->assertSame([DSN_BDD2, USER_BDD2, PASS_BDD2], Bdd::identifiants('bdd2'));
    }

    public function testUnNomLibreSeResoutEnDsnBddNom(): void
    {
        $this->assertSame(
            ['sqlite::memory:', 'utilisateur-secret-test', 'mot-de-passe-secret-test'],
            Bdd::identifiants('testsqlite')
        );
    }

    public function testLaFormeHistoriqueDsnNomResteAcceptee(): void
    {
        $this->assertSame(['sqlite::memory:', 'u-historique', 'p-historique'], Bdd::identifiants('historiquetest'));
    }

    public function testDsnBddNomLEmporteSurLaFormeHistorique(): void
    {
        $this->assertSame('sqlite::memory:', Bdd::identifiants('prioritetest')[0]);
    }

    public function testUtilisateurEtMotDePasseFacultatifs(): void
    {
        $this->assertSame(['sqlite::memory:', '', ''], Bdd::identifiants('sansutilisateur'));
    }

    public function testUneConnexionInconnueNommeLesConstantesAttendues(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DSN_BDD_INCONNUE_TEST');
        Bdd::identifiants('inconnue-test');
    }

    public function testUneConnexionPdoParNomPourLaRequete(): void
    {
        $a = new Bdd('testsqlite');
        $b = new Bdd('TestSqlite');
        $this->assertInstanceOf(\PDO::class, $a->bdd);
        $this->assertSame($a->bdd, $b->bdd);
        $this->assertSame($a->bdd, Bdd::connexion('testsqlite'));
        $this->assertSame(1, (int) $a->bdd->query('SELECT 1')->fetchColumn());
        $this->assertSame(\PDO::FETCH_ASSOC, $a->bdd->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    /**
     * @dataProvider definitionsDePage
     */
    public function testNomPourPage(array $page, string $attendu): void
    {
        $this->assertSame($attendu, Bdd::nomPourPage($page));
    }

    public function definitionsDePage(): array
    {
        return [
            'clé bdd du .model' => [['name' => 'x', 'bdd' => 'testsqlite'], 'testsqlite'],
            'sans clé bdd' => [['name' => 'x'], 'default'],
            'clé bdd vide' => [['name' => 'x', 'bdd' => ''], 'default'],
            'clé bdd avec espaces' => [['name' => 'x', 'bdd' => '  testsqlite '], 'testsqlite'],
        ];
    }

    public function testNomPourPageRefuseUneValeurQuiNEstPasUnNom(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Bdd::nomPourPage(['name' => 'x', 'bdd' => ['a', 'b']]);
    }

    public function testPourPageOuvreLaConnexionDuModele(): void
    {
        $this->assertSame(Bdd::connexion('testsqlite'), Bdd::pourPage(['name' => 'x', 'bdd' => 'testsqlite'])->bdd);
    }

    public function testExploiterNbResultatRenvoieRowCount(): void
    {
        $bdd = new Bdd('testsqlite');
        $bdd->bdd->exec('CREATE TABLE IF NOT EXISTS nb (id INTEGER PRIMARY KEY, v TEXT)');
        $bdd->bdd->exec('DELETE FROM nb');
        $bdd->bdd->exec("INSERT INTO nb (v) VALUES ('a'), ('a'), ('b')");

        // Sans paramètre lié : Caracter::normalise_ChaineDeCaracteres() (forceutf8)
        // émet une dépréciation utf8_decode() sous PHP 8.2+ (hors du périmètre de Bdd).
        $req = $bdd->faireBindRequete("UPDATE nb SET v = 'c' WHERE v = 'a'");

        $this->assertSame(2, $bdd->exploiterNbResultat($req));
    }

    public function testFaireBindRequeteJournaliseLEchecSansSecretEtRenvoieZero(): void
    {
        $bdd = new Bdd('testsqlite');
        $bdd->bdd->exec('CREATE TABLE IF NOT EXISTS stricte (id INTEGER PRIMARY KEY, v TEXT UNIQUE)');
        $bdd->bdd->exec("INSERT OR IGNORE INTO stricte (v) VALUES ('double')");
        $modeAvant = $bdd->bdd->getAttribute(\PDO::ATTR_ERRMODE);
        $bdd->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
        $journal = LOG_PATH . DIRECTORY_SEPARATOR . 'app.error.log';
        $tailleAvant = is_file($journal) ? filesize($journal) : 0;
        $agentAvant = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $_SERVER['HTTP_USER_AGENT'] = 'phpunit';

        try {
            $resultat = $bdd->faireBindRequete("INSERT INTO stricte (v) VALUES ('double')");
        } finally {
            $bdd->bdd->setAttribute(\PDO::ATTR_ERRMODE, $modeAvant);
            if ($agentAvant === null) {
                unset($_SERVER['HTTP_USER_AGENT']);
            } else {
                $_SERVER['HTTP_USER_AGENT'] = $agentAvant;
            }
        }

        $this->assertSame(0, $resultat);
        clearstatcache();
        $ajout = (string) file_get_contents($journal, false, null, $tailleAvant);
        $this->assertStringContainsString('[Bdd::faireBindRequete]', $ajout);
        $this->assertStringContainsString('testsqlite', $ajout);
        $this->assertStringContainsString('23000', $ajout);
        $this->assertStringContainsString('UNIQUE', $ajout);
        $this->assertStringNotContainsString('sqlite::memory:', $ajout);
        $this->assertStringNotContainsString('utilisateur-secret-test', $ajout);
        $this->assertStringNotContainsString('mot-de-passe-secret-test', $ajout);
    }
}
