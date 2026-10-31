<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Controlleur;
use PHPUnit\Framework\TestCase;

/**
 * Mode débogage strict (Environment::debug) et repli instanciate() des
 * réponses applicatives qui n'appellent pas parent::__construct().
 */
final class DebugEtReponsesTest extends TestCase
{
    /**
     * DEBUG est une constante : chaque valeur est testée dans un processus PHP
     * séparé, qui la définit avant l'autoload (constantes-par-defaut.php ne la
     * redéfinit donc pas).
     *
     * @dataProvider valeursDebug
     */
    public function testDebugStrict(?string $definition, string $attendu): void
    {
        $this->assertSame($attendu, $this->debugDansUnProcessus($definition));
    }

    public function valeursDebug(): array
    {
        return [
            'chaîne "false"' => ['"false"', 'false'],
            'chaîne "1"' => ['"1"', 'false'],
            'entier 1' => ['1', 'false'],
            'booléen true' => ['true', 'true'],
            'booléen false' => ['false', 'false'],
            'non définie (valeur par défaut)' => [null, 'false'],
        ];
    }

    public function testRepliInstanciateSansParentConstruct(): void
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'SansParentHttpReponse.php';
        $agentAvant = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $_SERVER['HTTP_USER_AGENT'] = 'curl/8.0';

        $application = new \stdClass();
        $application->url = new \stdClass();
        $application->url->page = ['name' => 'SansParent', 'params' => ['id' => '7']];
        $application->http = new class {
            public $method = 'GET';

            public function getData(): array
            {
                return ['cle' => 'valeur'];
            }
        };

        try {
            $controlleur = (new \ReflectionClass(Controlleur::class))->newInstanceWithoutConstructor();
            $controlleur->callHttpResponse($application);
        } finally {
            if ($agentAvant === null) {
                unset($_SERVER['HTTP_USER_AGENT']);
            } else {
                $_SERVER['HTTP_USER_AGENT'] = $agentAvant;
            }
        }

        $this->assertSame('get:{"id":"7"}:{"cle":"valeur"}', $controlleur->vue->ecran);
    }

    private function debugDansUnProcessus(?string $definition): string
    {
        $autoload = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $code = '$_SERVER["REQUEST_SCHEME"] = "http"; $_SERVER["HTTP_HOST"] = "localhost";'
            . ($definition === null ? '' : ' define("DEBUG", ' . $definition . ');')
            . ' require ' . var_export($autoload, true) . ';'
            . ' echo var_export(\MVC\Object\Environment::debug(), true);';

        $sortie = [];
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1', $sortie, $retour);
        $this->assertSame(0, $retour, implode("\n", $sortie));

        return trim(implode("\n", $sortie));
    }
}
