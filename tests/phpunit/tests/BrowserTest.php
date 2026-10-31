<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Browser;
use PHPUnit\Framework\TestCase;

/**
 * Browser::detection_mobile() : en-têtes WAP, User-Agent, puis get_browser()
 * seulement si browscap est configuré. Sans browscap, chaque cas est joué
 * dans un processus PHP lancé avec « -d browscap= ».
 */
final class BrowserTest extends TestCase
{
    private const NOKIA = 'Nokia6230/2.0 (04.44) Profile/MIDP-2.0 Configuration/CLDC-1.1';
    private const BUREAU = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0';

    private array $serveurAvant;

    protected function setUp(): void
    {
        $this->serveurAvant = $_SERVER;
        unset($_SERVER['HTTP_X_WAP_PROFILE'], $_SERVER['HTTP_PROFILE'], $_SERVER['HTTP_ACCEPT']);
        unset($_SERVER['HTTP_USER_AGENT']);
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serveurAvant;
    }

    /**
     * @dataProvider entetesMobiles
     */
    public function testEntetesEtUserAgentMobilesSansBrowscap(array $entetes): void
    {
        $this->assertSame('true', $this->detecterSansBrowscap($entetes));
    }

    public function entetesMobiles(): array
    {
        return [
            'profil WAP' => [['HTTP_X_WAP_PROFILE' => 'http://wap.exemple/profil.xml']],
            'en-tête Profile' => [['HTTP_PROFILE' => 'http://wap.exemple/profil.xml']],
            'Accept WAP' => [['HTTP_ACCEPT' => 'text/vnd.WAP.wml, */*']],
            'User-Agent Mobile' => [['HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone) Mobile/15E148 Safari/604.1']],
            'User-Agent Opera Mini' => [['HTTP_USER_AGENT' => 'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80) Presto/2.5']],
        ];
    }

    /**
     * @dataProvider entetesNonMobiles
     */
    public function testSansBrowscapLeRepliNeDetecteQueLesEntetes(array $entetes): void
    {
        $this->assertSame('false', $this->detecterSansBrowscap($entetes));
    }

    public function entetesNonMobiles(): array
    {
        return [
            'bureau' => [['HTTP_USER_AGENT' => self::BUREAU, 'HTTP_ACCEPT' => 'text/html']],
            'mobile connu du seul browscap' => [['HTTP_USER_AGENT' => self::NOKIA]],
            'aucun en-tête' => [[]],
        ];
    }

    public function testEnProcessusEntetesMobiles(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80) Presto/2.5';

        $this->assertTrue(Browser::detection_mobile());
    }

    public function testAvecBrowscapGetBrowserCompleteLaDetection(): void
    {
        if (!ini_get('browscap')) {
            $this->markTestSkipped('browscap non configuré dans php.ini');
        }

        $_SERVER['HTTP_USER_AGENT'] = self::NOKIA;
        $this->assertTrue(Browser::detection_mobile());

        $_SERVER['HTTP_USER_AGENT'] = self::BUREAU;
        $this->assertFalse(Browser::detection_mobile());
    }

    /**
     * Lance detection_mobile() dans un processus sans browscap ; la sortie
     * comprend tout avertissement émis, qui ferait échouer la comparaison.
     */
    private function detecterSansBrowscap(array $entetes): string
    {
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = 'error_reporting(E_ALL); ini_set("display_errors", "stderr");'
            . ' $_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ' unset($_SERVER["HTTP_USER_AGENT"]);'
            . ' foreach (' . var_export($entetes, true) . ' as $cle => $valeur) { $_SERVER[$cle] = $valeur; }'
            . ' require ' . var_export($autoload, true) . ';'
            . ' echo ini_get("browscap") === "" ? "" : "browscap actif ";'
            . ' echo var_export(\MVC\Classe\Browser::detection_mobile(), true);';

        $sortie = [];
        exec(
            escapeshellarg(PHP_BINARY) . ' -d browscap= -r ' . escapeshellarg($code) . ' 2>&1',
            $sortie,
            $retour
        );
        $this->assertSame(0, $retour, implode("\n", $sortie));

        return trim(implode("\n", $sortie));
    }
}
