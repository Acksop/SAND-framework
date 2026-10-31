<?php

declare(strict_types=1);

namespace Tests;

/**
 * Serveur intégré de PHP (php -S) lancé sur public/index.php pour les tests HTTP.
 *
 * Les constantes de test (fichier de clés, dépôt, maintenance...) sont définies
 * par un script routeur temporaire qui inclut ensuite public/index.php (php -S
 * n'applique pas auto_prepend_file à son routeur) : tout est écrit dans un
 * dossier temporaire, jamais dans Api/config/. Un fichier note à la fin de
 * chaque requête si la classe Controlleur a été chargée.
 */
final class ServeurPhpIntegre
{
    /** @var resource|null */
    private $processus = null;
    private string $dossier;
    private int $port;

    /**
     * @param array<string, mixed>  $constantes
     * @param array<string, string> $cles       empreinte SHA-256 => client, écrites dans un api_keys.php temporaire
     */
    public function __construct(string $racine, array $constantes = [], array $cles = [])
    {
        $this->dossier = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sand-api-' . bin2hex(random_bytes(6));
        mkdir($this->dossier, 0700);
        $codeCles = "<?php\ndefine('API_KEYS', " . var_export($cles, true) . ");\n";
        file_put_contents($this->fichier('api_keys.php'), $codeCles);
        $constantes += [
            'API_FICHIER_CLES' => $this->fichier('api_keys.php'),
            'SAND_TEST_JOURNAL_DEPOT' => $this->fichier('depot.log'),
        ];
        $routeur = $this->fichier('routeur.php');
        file_put_contents($routeur, $this->codeRouteur($constantes, $racine . '/public/index.php'));

        $socket = stream_socket_server('tcp://127.0.0.1:0');
        $this->port = (int) substr((string) strrchr((string) stream_socket_get_name($socket, false), ':'), 1);
        fclose($socket);

        $commande = [
            PHP_BINARY, '-d', 'display_errors=0', '-d', 'log_errors=0',
            '-S', '127.0.0.1:' . $this->port, '-t', $racine . '/public', $routeur,
        ];
        $journal = ['file', $this->fichier('serveur.log'), 'a'];
        $this->processus = proc_open($commande, [0 => ['pipe', 'r'], 1 => $journal, 2 => $journal], $tubes, $racine);
        $this->attendre();
    }

    public function fichier(string $nom): string
    {
        return $this->dossier . DIRECTORY_SEPARATOR . $nom;
    }

    public function lire(string $nom): string
    {
        return is_file($this->fichier($nom)) ? (string) file_get_contents($this->fichier($nom)) : '';
    }

    /**
     * Requête HTTP brute (la cible est envoyée telle quelle : //, %2F, majuscules).
     *
     * @param array<string, string> $entetes
     * @return array{statut: int, entetes: string, corps: string}
     */
    public function requete(string $methode, string $cible, array $entetes = [], string $corps = ''): array
    {
        $flux = fsockopen('127.0.0.1', $this->port, $code, $erreur, 5);
        $texte = $methode . ' ' . $cible . " HTTP/1.0\r\nHost: 127.0.0.1\r\nConnection: close\r\n";
        foreach ($entetes + ['Content-Length' => (string) strlen($corps)] as $nom => $valeur) {
            $texte .= $nom . ': ' . $valeur . "\r\n";
        }
        fwrite($flux, $texte . "\r\n" . $corps);
        $reponse = (string) stream_get_contents($flux);
        fclose($flux);
        [$tete, $contenu] = array_pad(explode("\r\n\r\n", $reponse, 2), 2, '');
        preg_match('#^HTTP/\S+ (\d{3})#', $tete, $statut);

        return ['statut' => (int) ($statut[1] ?? 0), 'entetes' => $tete, 'corps' => $contenu];
    }

    public function arreter(): void
    {
        if (is_resource($this->processus)) {
            proc_terminate($this->processus);
            proc_close($this->processus);
        }
        $this->processus = null;
        foreach (glob($this->dossier . DIRECTORY_SEPARATOR . '*') ?: [] as $fichier) {
            unlink($fichier);
        }
        if (is_dir($this->dossier)) {
            rmdir($this->dossier);
        }
    }

    public function __destruct()
    {
        $this->arreter();
    }

    private function attendre(): void
    {
        for ($essai = 0; $essai < 100; $essai++) {
            $flux = @fsockopen('127.0.0.1', $this->port, $code, $erreur, 0.1);
            if ($flux !== false) {
                fclose($flux);

                return;
            }
            usleep(50000);
        }
        $this->arreter();
        throw new \RuntimeException('php -S ne répond pas sur le port ' . $this->port);
    }

    /**
     * @param array<string, mixed> $constantes
     */
    private function codeRouteur(array $constantes, string $index): string
    {
        $code = "<?php\n\$_SERVER['REQUEST_SCHEME'] = 'http';\n";
        foreach ($constantes as $nom => $valeur) {
            $code .= 'define(' . var_export($nom, true) . ', ' . var_export($valeur, true) . ");\n";
        }
        $factice = var_export(__DIR__ . DIRECTORY_SEPARATOR . 'DepotSoumissionsFactice.php', true);
        $marque = var_export($this->fichier('controlleur.txt'), true);

        return $code . "spl_autoload_register(static function (string \$classe): void {\n"
            . "    if (\$classe === 'Tests\\\\DepotSoumissionsFactice') { require $factice; }\n"
            . "});\n"
            . "register_shutdown_function(static function (): void {\n"
            . "    \$charge = class_exists('MVC\\\\Classe\\\\Controlleur', false) ? 'oui' : 'non';\n"
            . "    file_put_contents($marque, \$charge);\n"
            . "});\n"
            . 'require ' . var_export($index, true) . ";\n";
    }
}
