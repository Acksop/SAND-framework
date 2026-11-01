<?php

namespace SAND\Classe;

class Bdd
{
    /**
     * Format d'un nom de connexion : minuscule, chiffres et _, 32 caractères au plus
     * (modificateur D : un saut de ligne final n'est pas toléré par $).
     */
    public const FORMAT_NOM = '/^[a-z][a-z0-9_]{0,31}$/D';

    /**
     * Longueur maximale du message du pilote recopié dans le journal.
     */
    private const LONGUEUR_MESSAGE_JOURNAL = 120;

    public $bdd;

    /**
     * Nom de la connexion utilisée par cette instance (validé, jamais le DSN).
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
     * @param string $bdd nom de la connexion, autorisé par BDD_CONNEXIONS_AUTORISEES
     *                    et déclaré dans bdd-parameters.php
     *                    (DSN_BDD_<NOM>, USER_BDD_<NOM>, PASS_BDD_<NOM>)
     */
    public function __construct($bdd = 'default')
    {
        $this->nom = self::nomValide($bdd);
        $this->bdd = self::connexion($this->nom);
    }

    /**
     * Connexion PDO du nom donné, ouverte une seule fois par requête.
     *
     * @throws \RuntimeException si la connexion ne peut pas être ouverte
     *                           (sans l'exception PDO d'origine, qui porte les identifiants)
     */
    public static function connexion(string $nom): \PDO
    {
        [$dsn, $utilisateur, $motDePasse] = self::identifiants($nom);
        if (!isset(self::$connexions[$nom])) {
            try {
                $pdo = new \PDO($dsn, $utilisateur, $motDePasse);
            } catch (\PDOException $e) {
                $etat = preg_match('/SQLSTATE\[([0-9A-Z]{5})\]/', $e->getMessage(), $m) ? $m[1] : 'inconnu';
                Logger::addLog('error', '[Bdd::connexion] connexion « ' . $nom . ' » impossible : SQLSTATE ' . $etat);
                throw new \RuntimeException('Connexion BDD « ' . $nom . ' » impossible');
            }
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            self::$connexions[$nom] = $pdo;
        }

        return self::$connexions[$nom];
    }

    /**
     * Constantes de la connexion : DSN_BDD_<NOM> ; pour un nom bdd<chiffres>
     * seulement, la forme historique DSN_<NOM> (DSN_BDD1, DSN_BDD2).
     * USER_ et PASS_ sont facultatives.
     *
     * @return array{0: string, 1: string, 2: string} [dsn, utilisateur, mot de passe]
     * @throws \InvalidArgumentException nom mal formé ou absent de BDD_CONNEXIONS_AUTORISEES
     * @throws \RuntimeException         si aucune constante DSN n'est définie
     */
    public static function identifiants(string $nom): array
    {
        $nom = self::nomValide($nom);
        $autorises = defined('BDD_CONNEXIONS_AUTORISEES') && is_array(BDD_CONNEXIONS_AUTORISEES)
            ? BDD_CONNEXIONS_AUTORISEES
            : [];
        if (!in_array($nom, $autorises, true)) {
            throw new \InvalidArgumentException(
                'Connexion BDD « ' . $nom . ' » non autorisée : l\'ajouter à BDD_CONNEXIONS_AUTORISEES'
            );
        }

        $suffixe = strtoupper($nom);
        $cles = ['BDD_' . $suffixe];
        if (preg_match('/^bdd[0-9]+$/D', $nom)) {
            $cles[] = $suffixe;
        }
        foreach ($cles as $cle) {
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
     * Vérifie le format d'un nom de connexion. Le message d'erreur ne
     * reprend jamais la valeur reçue.
     *
     * @throws \InvalidArgumentException
     */
    public static function nomValide($nom): string
    {
        if (!is_string($nom) || !preg_match(self::FORMAT_NOM, $nom)) {
            throw new \InvalidArgumentException(
                'Nom de connexion BDD invalide (format attendu : ^[a-z][a-z0-9_]{0,31}$)'
            );
        }

        return $nom;
    }

    /**
     * Connexion déclarée par une définition de page issue d'un fichier .model
     * (clé « bdd »), sinon 'default'.
     *
     * Ne jamais passer de paramètres d'URL ($_GET, page['all_params']) : le nom
     * de la connexion doit venir du .model, pas du visiteur.
     */
    public static function pourPage(array $page): self
    {
        return new self(self::nomPourPage($page));
    }

    /**
     * Nom de connexion déclaré par la clé « bdd » d'une définition de page.
     *
     * @throws \InvalidArgumentException si la clé « bdd » n'est pas un nom valide
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

        return $nom === '' ? 'default' : self::nomValide($nom);
    }

    public function faireSQLRequete($sql)
    {
        $sql = \SAND\Classe\Caracter::avoid_sql_injection($sql);
        $req = $this->bdd->query($sql);
        if (!$req) {
            // Journalisé (SQLSTATE, code et message tronqué du pilote), jamais affiché
            $this->journaliserEchec('query()', $this->bdd->errorInfo(), 'faireSQLRequete');
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
     * Journalise SQLSTATE, code du pilote et message du pilote (sur une ligne,
     * tronqué) avec le nom validé de la connexion. Jamais de DSN, d'utilisateur
     * ni de mot de passe.
     */
    private function journaliserEchec(string $etape, array $erreur, string $methode = 'faireBindRequete'): int
    {
        $message = preg_replace('/[\x00-\x1F\x7F]+/', ' ', (string) ($erreur[2] ?? ''));
        $message = mb_substr($message, 0, self::LONGUEUR_MESSAGE_JOURNAL);
        Logger::addLog(
            'error',
            '[Bdd::' . $methode . '] connexion « ' . $this->nom . ' » : ' . $etape . ' a échoué : SQLSTATE '
            . preg_replace('/[^0-9A-Za-z]/', '', (string) ($erreur[0] ?? ''))
            . ' | code ' . (int) ($erreur[1] ?? 0) . ' | ' . $message
        );

        return 0;
    }

    public function exploiterResultat($req)
    {
        // Chaque ligne est normalisée (chaînes seulement, les autres types
        // sont rendus tels quels). L'ancienne boucle travaillait sur une copie.
        return array_map([Caracter::class, 'corrigerUtf8'], $req->fetchAll());
    }

    /**
     * Nombre de lignes touchées par la dernière requête (PDOStatement::rowCount()).
     */
    public function exploiterNbResultat($req)
    {
        return $req->rowCount();
    }
}
