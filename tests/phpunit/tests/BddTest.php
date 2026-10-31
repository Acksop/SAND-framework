<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Bdd;
use PHPUnit\Framework\TestCase;

// Connexions de test : SQLite uniquement, aucune base réelle n'est touchée.
// Les noms sont autorisés par BDD_CONNEXIONS_AUTORISEES dans amorce.php.
// SQLite ignore l'utilisateur et le mot de passe : ils servent à vérifier
// qu'ils n'apparaissent jamais dans un journal ou un message.
defined('DSN_BDD_TESTSQLITE') or define('DSN_BDD_TESTSQLITE', 'sqlite::memory:');
defined('USER_BDD_TESTSQLITE') or define('USER_BDD_TESTSQLITE', 'utilisateur-secret-test');
defined('PASS_BDD_TESTSQLITE') or define('PASS_BDD_TESTSQLITE', 'mot-de-passe-secret-test');
// Forme historique DSN_<NOM>, acceptée pour bdd<chiffres> seulement
defined('DSN_BDD7') or define('DSN_BDD7', 'sqlite::memory:');
defined('USER_BDD7') or define('USER_BDD7', 'u-historique');
defined('PASS_BDD7') or define('PASS_BDD7', 'p-historique');
// bdd8 : les deux formes, DSN_BDD_BDD8 l'emporte
defined('DSN_BDD_BDD8') or define('DSN_BDD_BDD8', 'sqlite::memory:');
defined('DSN_BDD8') or define('DSN_BDD8', 'sqlite:/ne/doit/pas/servir');
// Forme sans BDD_ pour un nom qui n'est pas bdd<chiffres> : refusée
defined('DSN_PRIORITETEST') or define('DSN_PRIORITETEST', 'sqlite::memory:');
defined('DSN_BDD_SANSUTILISATEUR') or define('DSN_BDD_SANSUTILISATEUR', 'sqlite::memory:');
// Déclarée mais absente de la liste blanche
defined('DSN_BDD_HORSLISTE') or define('DSN_BDD_HORSLISTE', 'sqlite::memory:');
// Ouverture impossible (dossier inexistant)
defined('DSN_BDD_CASSEE') or define('DSN_BDD_CASSEE', 'sqlite:/dossier/inexistant/sand-g3/base.sqlite');
defined('USER_BDD_CASSEE') or define('USER_BDD_CASSEE', 'utilisateur-cassee-secret');
defined('PASS_BDD_CASSEE') or define('PASS_BDD_CASSEE', 'pass-cassee-secret');

/**
 * Connexions nommées (format, liste blanche), Bdd::pourPage(), journaux
 * assainis de faireBindRequete() et exploiterNbResultat().
 */
final class BddTest extends TestCase
{
    public function testDefaultEtBdd1Bdd2RestentCompatibles(): void
    {
        $this->assertSame([DSN_BDD_DEFAULT, USER_BDD_DEFAULT, PASS_BDD_DEFAULT], Bdd::identifiants('default'));
        $this->assertSame([DSN_BDD1, USER_BDD1, PASS_BDD1], Bdd::identifiants('bdd1'));
        $this->assertSame([DSN_BDD2, USER_BDD2, PASS_BDD2], Bdd::identifiants('bdd2'));
    }

    public function testListeBlancheParDefaut(): void
    {
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ' require ' . var_export($autoload, true) . '; echo json_encode(BDD_CONNEXIONS_AUTORISEES);';
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);

        $this->assertSame(0, $retour, implode("\n", $sortie));
        $this->assertSame('["default","bdd1","bdd2"]', trim(implode("\n", $sortie)));
    }

    public function testUnNomLegitimeSeResoutEnDsnBddNom(): void
    {
        $this->assertSame(
            ['sqlite::memory:', 'utilisateur-secret-test', 'mot-de-passe-secret-test'],
            Bdd::identifiants('testsqlite')
        );
        $this->assertSame(['sqlite::memory:', '', ''], Bdd::identifiants('sansutilisateur'));
    }

    public function testFormeHistoriqueReserveeAuxNomsBddChiffres(): void
    {
        $this->assertSame(['sqlite::memory:', 'u-historique', 'p-historique'], Bdd::identifiants('bdd7'));
        $this->assertSame('sqlite::memory:', Bdd::identifiants('bdd8')[0]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DSN_BDD_PRIORITETEST');
        Bdd::identifiants('prioritetest');
    }

    public function testUneConnexionAutoriseeSansConstanteEstInconnue(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DSN_BDD_ABSENTE');
        Bdd::identifiants('absente');
    }

    public function testUnNomHorsListeEstRefuse(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('BDD_CONNEXIONS_AUTORISEES');
        new Bdd('horsliste');
    }

    /**
     * @dataProvider nomsInvalides
     */
    public function testUnNomMalFormeEstRefuseSansEtreRecopie(string $nom): void
    {
        try {
            Bdd::identifiants($nom);
            $this->fail('Nom accepté : ' . json_encode($nom));
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('invalide', $e->getMessage());
            $this->assertStringNotContainsString('<script>', $e->getMessage());
        }
    }

    public function nomsInvalides(): array
    {
        return [
            'majuscules' => ['TestSqlite'],
            'tiret' => ['test-sqlite'],
            'chiffre en tête' => ['1bdd'],
            'vide' => [''],
            '33 caractères' => ['a' . str_repeat('b', 32)],
            'saut de ligne' => ["default\n"],
            'chemin' => ['../default'],
            'injection' => ['x<script>'],
        ];
    }

    public function testUneConnexionPdoParNomPourLaRequete(): void
    {
        $a = new Bdd('testsqlite');
        $b = new Bdd('testsqlite');
        $this->assertInstanceOf(\PDO::class, $a->bdd);
        $this->assertSame($a->bdd, $b->bdd);
        $this->assertSame($a->bdd, Bdd::connexion('testsqlite'));
        $this->assertSame(1, (int) $a->bdd->query('SELECT 1')->fetchColumn());
        $this->assertSame(\PDO::FETCH_ASSOC, $a->bdd->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    public function testOuvertureImpossibleSansIdentifiantsNiExceptionDOrigine(): void
    {
        $exception = null;
        $ajout = $this->journalPendant(function () use (&$exception): void {
            try {
                new Bdd('cassee');
            } catch (\RuntimeException $e) {
                $exception = $e;
            }
        });

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertSame('Connexion BDD « cassee » impossible', $exception->getMessage());
        $this->assertNull($exception->getPrevious());
        $this->assertStringContainsString('[Bdd::connexion] connexion « cassee » impossible : SQLSTATE HY000', $ajout);
        foreach ([$ajout, $exception->getMessage(), $exception->getTraceAsString()] as $texte) {
            $this->assertStringNotContainsString('/dossier/inexistant', $texte);
            $this->assertStringNotContainsString('utilisateur-cassee-secret', $texte);
            $this->assertStringNotContainsString('pass-cassee-secret', $texte);
        }
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

    public function testNomPourPageRefuseUnTableau(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Bdd::nomPourPage(['name' => 'x', 'bdd' => ['a', 'b']]);
    }

    public function testNomPourPageRefuseUnNomMalForme(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Bdd::nomPourPage(['name' => 'x', 'bdd' => 'Bdd-X']);
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

        // Requête avec paramètres liés : voir CaracterTest (aucune dépréciation).
        $req = $bdd->faireBindRequete("UPDATE nb SET v = 'c' WHERE v = 'a'");

        $this->assertSame(2, $bdd->exploiterNbResultat($req));
    }

    public function testEchecDExecutionJournaliseSansSecretEtRenvoieZero(): void
    {
        $bdd = new Bdd('testsqlite');
        $bdd->bdd->exec('CREATE TABLE IF NOT EXISTS stricte (id INTEGER PRIMARY KEY, v TEXT UNIQUE)');
        $bdd->bdd->exec("INSERT OR IGNORE INTO stricte (v) VALUES ('double')");

        $resultat = null;
        $ajout = $this->journalPendant(function () use ($bdd, &$resultat): void {
            $resultat = $bdd->faireBindRequete("INSERT INTO stricte (v) VALUES ('double')");
        }, $bdd);

        $this->assertSame(0, $resultat);
        $this->assertStringContainsString(
            '[Bdd::faireBindRequete] connexion « testsqlite » : execute() a échoué : SQLSTATE 23000 | code 19 | UNIQUE',
            $ajout
        );
        $this->assertStringNotContainsString('sqlite::memory:', $ajout);
        $this->assertStringNotContainsString('utilisateur-secret-test', $ajout);
        $this->assertStringNotContainsString('mot-de-passe-secret-test', $ajout);
    }

    public function testLeMessageDuPiloteEstSurUneLigneEtTronque(): void
    {
        $bdd = new Bdd('testsqlite');
        $table = "inj\n[faux] ligne injectée " . str_repeat('z', 300);

        $resultat = null;
        $ajout = $this->journalPendant(function () use ($bdd, $table, &$resultat): void {
            $resultat = $bdd->faireBindRequete('SELECT * FROM "' . $table . '"');
        }, $bdd);

        $this->assertSame(0, $resultat);
        $this->assertStringContainsString('prepare() a échoué', $ajout);
        $this->assertStringContainsString('inj [faux] ligne', $ajout);
        $this->assertStringNotContainsString("\n[faux]", $ajout);
        $this->assertStringNotContainsString(str_repeat('z', 120), $ajout);
    }

    /**
     * Exécute $action et renvoie ce qu'elle a ajouté à app.error.log. Si
     * $bdd est donné, sa connexion passe en ERRMODE_SILENT le temps de l'action.
     */
    private function journalPendant(callable $action, ?Bdd $bdd = null): string
    {
        $journal = LOG_PATH . DIRECTORY_SEPARATOR . 'app.error.log';
        clearstatcache();
        $tailleAvant = is_file($journal) ? filesize($journal) : 0;
        $agentAvant = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $_SERVER['HTTP_USER_AGENT'] = 'phpunit';
        $modeAvant = $bdd ? $bdd->bdd->getAttribute(\PDO::ATTR_ERRMODE) : null;
        if ($bdd) {
            $bdd->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
        }

        try {
            $action();
        } finally {
            if ($bdd) {
                $bdd->bdd->setAttribute(\PDO::ATTR_ERRMODE, $modeAvant);
            }
            if ($agentAvant === null) {
                unset($_SERVER['HTTP_USER_AGENT']);
            } else {
                $_SERVER['HTTP_USER_AGENT'] = $agentAvant;
            }
        }
        clearstatcache();

        return (string) file_get_contents($journal, false, null, $tailleAvant);
    }
}
