<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Url;
use MVC\Object\Guard;
use MVC\Object\Session;
use MVC\Object\XssToken;
use PHPUnit\Framework\TestCase;

/**
 * Objets de session : ouverture de session, refus d'accès, Guard, accesseurs
 * de l'utilisateur et jeton XSS.
 */
final class SessionTest extends TestCase
{
    private array $sessionAvant = [];
    private ?string $uriAvant = null;

    protected function setUp(): void
    {
        $this->sessionAvant = $_SESSION ?? [];
        $this->uriAvant = $_SERVER['REQUEST_URI'] ?? null;
        $_SESSION = [];
        unset($_SERVER['REQUEST_URI']);
    }

    protected function tearDown(): void
    {
        $_SESSION = $this->sessionAvant;
        if ($this->uriAvant === null) {
            unset($_SERVER['REQUEST_URI']);
        } else {
            $_SERVER['REQUEST_URI'] = $this->uriAvant;
        }
    }

    public function testConstantesParDefaut(): void
    {
        $this->assertSame('id_user', SESSION_CLE_ID_UTILISATEUR);
        $this->assertSame('acl_admin', SESSION_CLE_ACL);
        $this->assertSame('index', PAGE_IDENTIFICATION);
    }

    /**
     * @dataProvider sessionsConnexion
     */
    public function testEstConnecte(array $session, bool $attendu): void
    {
        $_SESSION = $session;

        $this->assertSame($attendu, Session::estConnecte());
    }

    public function sessionsConnexion(): array
    {
        return [
            'session vide' => [[], false],
            'visiteur anonymous' => [['user_login' => 'anonymous', 'acl_admin' => 0], false],
            'login vide' => [['user_login' => ''], false],
            'utilisateur CAS' => [['user_login' => 'jdupont', 'acl_admin' => 0], true],
            'hôte qui ne pose que id_user' => [['id_user' => 42], true],
            'identifiant 0' => [['id_user' => 0], true],
            'identifiant nul' => [['user_login' => 'anonymous', 'id_user' => null], false],
        ];
    }

    public function testUtilisateurCasSansDroitsRefuseVersAccesRefuse(): void
    {
        $_SESSION = ['user_login' => 'jdupont', 'acl_admin' => 0];

        $this->assertFalse((new \ReflectionMethod(Guard::class, 'autorise'))->invoke(null, [1]));
        $this->assertSame(Url::link_rewrite(false, 'error-access-denied', []), $this->cibleRefus());
    }

    public function testVisiteurAnonymousRenvoyeVersLaPageIdentification(): void
    {
        $_SESSION = ['user_login' => 'anonymous', 'acl_admin' => 0];

        $this->assertSame(Url::link_rewrite(false, PAGE_IDENTIFICATION), $this->cibleRefus());
    }

    public function testPasDeBoucleSurLaPageIdentification(): void
    {
        $_SERVER['REQUEST_URI'] = Url::link_rewrite(false, PAGE_IDENTIFICATION) . '?x=1';

        $this->assertSame(Url::link_rewrite(false, 'error-access-denied', []), $this->cibleRefus());
    }

    public function testPasDeBoucleSurLaPageAccesRefuse(): void
    {
        $_SESSION = ['user_login' => 'jdupont'];
        $_SERVER['REQUEST_URI'] = Url::link_rewrite(false, 'error-access-denied', []);

        $this->assertNull($this->cibleRefus());
    }

    public function testVisiteurNonConnecteRenvoyeVersLaPageIdentification(): void
    {
        $this->assertSame(Url::link_rewrite(false, PAGE_IDENTIFICATION), $this->cibleRefus());
    }

    public function testSessionExpireeRenvoyeeVersLaPageIdentification(): void
    {
        $_SESSION['user_login'] = 'anonymous';
        $_SESSION[SESSION_CLE_ID_UTILISATEUR] = null;

        $this->assertSame(Url::link_rewrite(false, PAGE_IDENTIFICATION), $this->cibleRefus());
    }

    public function testUtilisateurConnecteRenvoyeVersAccesRefuse(): void
    {
        $_SESSION[SESSION_CLE_ID_UTILISATEUR] = 42;

        $this->assertSame(Url::link_rewrite(false, 'error-access-denied', []), $this->cibleRefus());
    }

    public function testAccesseursUtilisateur(): void
    {
        $this->assertNull(Session::getUser());
        $this->assertNull(Session::getIdUser());

        $_SESSION['user_login'] = 'emmanuel';
        $_SESSION[SESSION_CLE_ID_UTILISATEUR] = 42;

        $this->assertSame('emmanuel', Session::getUser());
        $this->assertSame(42, Session::getIdUser());
    }

    /**
     * @dataProvider decisionsGuard
     */
    public function testGuardDecision($acl, array $autorises, bool $attendu): void
    {
        if ($acl !== null) {
            $_SESSION[SESSION_CLE_ACL] = $acl;
        }
        $methode = new \ReflectionMethod(Guard::class, 'autorise');

        $this->assertSame($attendu, $methode->invoke(null, $autorises));
    }

    public function decisionsGuard(): array
    {
        return [
            'acl absente' => [null, [1], false],
            'acl autorisée' => [1, [1], true],
            'acl lue en chaîne' => ['1', [1], true],
            'acl parmi plusieurs' => [3, [1, 3], true],
            'acl non autorisée' => [0, [1], false],
            'aucune acl autorisée' => [1, [], false],
            'acl booléenne' => [true, [1], false],
            'acl flottante' => [1.0, [1], false],
            'acl chaîne non entière' => ['1.0', [1], false],
            'acl chaîne non numérique' => ['admin', [1], false],
        ];
    }

    public function testGuardExigerLaissePasserUneAclAutorisee(): void
    {
        $_SESSION['acl_admin'] = 1;

        Guard::exiger(1);
        Session::checkACL_admin();

        $this->addToAssertionCount(1);
    }

    public function testXssTokenGetNewRenvoieLeJeton(): void
    {
        $jeton = XssToken::getNew();

        $this->assertMatchesRegularExpression('/^xss[0-9a-f]{25}$/', $jeton);
        $this->assertSame($jeton, $_SESSION['xss_token']);
    }

    /**
     * Une session déjà ouverte par l'hôte n'est pas rouverte (notice
     * « session already active »).
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSessionStartNeRouvrePasUneSessionActive(): void
    {
        ini_set('session.use_cookies', '0');
        ini_set('session.cache_limiter', '');
        ini_set('session.save_path', sys_get_temp_dir());
        session_start();

        Session::sessionStart();

        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
        session_destroy();
    }

    /**
     * checkSession() sans utilisateur refuse l'accès sans erreur fatale
     * (l'appel non qualifié à Url visait MVC\Object\Url, inexistante).
     */
    public function testCheckSessionSansUtilisateurSansErreurFatale(): void
    {
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ' require ' . var_export($autoload, true) . ';'
            . ' $_SESSION = []; \MVC\Object\Session::checkSession(); echo "non refusé";';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);

        $this->assertSame(0, $retour, implode("\n", $sortie));
        $this->assertSame('', trim(implode("\n", $sortie)));
    }

    /**
     * checkSession() vérifie que la session est initialisée : le visiteur
     * anonymous du squelette n'est pas refusé (sinon die()).
     */
    public function testCheckSessionLaissePasserLeVisiteurAnonymous(): void
    {
        $_SESSION = ['user_login' => 'anonymous', 'acl_admin' => 0];

        Session::checkSession();

        $this->addToAssertionCount(1);
    }

    private function cibleRefus(): ?string
    {
        return (new \ReflectionMethod(Session::class, 'cibleRefus'))->invoke(null);
    }
}
