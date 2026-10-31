<?php

namespace MVC\Classe;

class Bdd
{
    public $bdd;

    /**
     * Nom de la connexion utilisée par cette instance (jamais le DSN).
     */
    private string $nom;

    /**
     * Une connexion PDO par nom, ouverte à la première utilisation et
     * partagée pendant la requête.
     *
     * @var array<string, \PDO>
     */
    private static array $connexions = [];

    /**
     * @param string $bdd nom de la connexion : 'default', 'bdd1', 'bdd2' ou tout
     *                    nom déclaré dans bdd-parameters.php
     *                    (DSN_BDD_<NOM>, USER_BDD_<NOM>, PASS_BDD_<NOM>)
     */
    public function __construct($bdd = 'default')
    {
        $this->nom = (string) $bdd;
        $this->bdd = self::connexion($this->nom);
    }

    /**
     * Connexion PDO du nom donné, ouverte une seule fois par requête.
     */
    public static function connexion(string $nom): \PDO
    {
        $cle = self::suffixe($nom);
        if (!isset(self::$connexions[$cle])) {
            [$dsn, $utilisateur, $motDePasse] = self::identifiants($nom);
            $pdo = new \PDO($dsn, $utilisateur, $motDePasse);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            self::$connexions[$cle] = $pdo;
        }

        return self::$connexions[$cle];
    }

    /**
     * Constantes de la connexion : DSN_BDD_<NOM>, puis la forme historique
     * DSN_<NOM> (DSN_BDD1, DSN_BDD2). USER_ et PASS_ sont facultatives.
     *
     * @return array{0: string, 1: string, 2: string} [dsn, utilisateur, mot de passe]
     * @throws \RuntimeException si aucune des deux formes n'est définie
     */
    public static function identifiants(string $nom): array
    {
        $suffixe = self::suffixe($nom);
        foreach (['BDD_' . $suffixe, $suffixe] as $cle) {
            if (defined('DSN_' . $cle)) {
                return [
                    (string) constant('DSN_' . $cle),
                    defined('USER_' . $cle) ? (string) constant('USER_' . $cle) : '',
                    defined('PASS_' . $cle) ? (string) constant('PASS_' . $cle) : '',
                ];
            }
        }

        throw new \RuntimeException(
            'Connexion BDD « ' . $nom . ' » inconnue : définir DSN_BDD_' . $suffixe . ', USER_BDD_' . $suffixe
            . ' et PASS_BDD_' . $suffixe . ' dans application/config/bdd-parameters.php'
        );
    }

    /**
     * Connexion déclarée par une définition de page issue d'un fichier .model
     * (clé « bdd »), sinon 'default'.
     *
     * Ne jamais passer de paramètres d'URL ($_GET, page['params']) : le nom
     * de la connexion doit venir du .model, pas du visiteur.
     */
    public static function pourPage(array $page): self
    {
        return new self(self::nomPourPage($page));
    }

    /**
     * Nom de connexion déclaré par la clé « bdd » d'une définition de page.
     *
     * @throws \InvalidArgumentException si la clé « bdd » n'est pas une chaîne
     */
    public static function nomPourPage(array $page): string
    {
        if (!isset($page['bdd'])) {
            return 'default';
        }
        if (!is_string($page['bdd'])) {
            throw new \InvalidArgumentException('La clé « bdd » du .model doit être un nom de connexion');
        }
        $nom = trim($page['bdd']);

        return $nom === '' ? 'default' : $nom;
    }

    /**
     * Suffixe normalisé : 'bdd1' et 'BDD1' partagent la même connexion.
     */
    private static function suffixe(string $nom): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9_]/', '_', $nom));
    }

    public function faireSQLRequete($sql)
    {
        $sql = \MVC\Classe\Caracter::avoid_sql_injection($sql);
        $req = $this->bdd->query($sql);
        // Print Pdo::ERRORs
        if (!$req && (ENV == 'TEST' || ENV == 'DEVEL')) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->bdd->errorInfo());
            print_r($sql);
        }
        return $req;
    }

    public function getLastInsertId()
    {
        return $this->bdd->lastInsertId();
    }

    /**
     *
     * Exemple:
     * $sql = "SELECT * FROM annonce WHERE cat_id = :categorie and ann_est_valide = 1";
     * $req = $bdd->faireBindRequete($sql,
     *                  array(
     *                     array('categorie', $categorie, \PDO::PARAM_INT),
     *                  )
     *         );
     * $data = $bdd->exploiterResultat($req)
     *
     * En cas d'échec (préparation, exécution ou PDOException), l'erreur est
     * journalisée dans app.error.log et la méthode renvoie 0.
     *
     * @param $sql
     * @param array|null $params
     * @return int|\PDOStatement
     */
    public function faireBindRequete($sql, ?array $params = null)
    {
        try {
            $req = $this->bdd->prepare($sql);
            if ($req === false) {
                return $this->journaliserEchec('prepare()', $this->bdd->errorInfo());
            }
            if ($params) {
                foreach ($params as $value) {
                    $value[1] = Caracter::normalise_ChaineDeCaracteres($value[1]);
                    if ($value[2] !== 'VALUE') {
                        $req->bindParam(':' . $value[0], $value[1], $value[2]);
                    } else {
                        $req->bindValue(':' . $value[0], $value[1]);
                    }
                }
            }
            if (!$req->execute()) {
                return $this->journaliserEchec('execute()', $req->errorInfo());
            }
        } catch (\PDOException $pdo_e) {
            return $this->journaliserEchec('PDOException', $pdo_e->errorInfo ?? [$pdo_e->getCode()]);
        }

        return $req;
    }

    /**
     * Journalise errorInfo() (SQLSTATE, code du pilote, message) avec le nom
     * de la connexion. Jamais de DSN, d'utilisateur ni de mot de passe.
     */
    private function journaliserEchec(string $etape, array $erreur): int
    {
        Logger::addLog(
            'error',
            '[Bdd::faireBindRequete] connexion « ' . $this->nom . ' » : ' . $etape . ' a échoué : '
            . implode(' | ', array_map('strval', $erreur))
        );

        return 0;
    }

    public function exploiterResultat($req)
    {
        $res = $req->fetchAll();
        foreach ($res as $data) {
            foreach ($data as $key => $row) {
                if (is_string($row)) {
                    $row = Caracter::normalise_ChaineDeCaracteres($row);
                }
                $data[$key] = $row;
            }
        }
        return $res;
    }

    /**
     * Nombre de lignes touchées par la dernière requête (PDOStatement::rowCount()).
     */
    public function exploiterNbResultat($req)
    {
        return $req->rowCount();
    }
}
