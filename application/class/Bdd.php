<?php

namespace MVC\Classe;

class Bdd
{
    public $bdd;

    public function __construct($bdd = 'bdd1')
    {
        switch ($bdd) {
            case 'bdd1':
                $this->bdd = new \PDO(DSN_BDD1, USER_BDD1, PASS_BDD1);
                break;
            case 'bdd2':
                $this->bdd = new \PDO(DSN_BDD2, USER_BDD2, PASS_BDD2);
                break;
            default:
                $this->bdd = new \PDO(DSN_BDD_DEFAULT, USER_BDD_DEFAULT, PASS_BDD_DEFAULT);
        }
        $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        $this->bdd->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function faireSQLRequete($sql)
    {
        $req = $this->bdd->query($sql);
        return $req;
    }

    public function getLastInsertId(){
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
     *
     * @param $sql
     * @param array|null $params
     * @return bool|\PDOStatement
     */
    public function faireBindRequete($sql, array $params = null)
    {
        $req = $this->bdd->prepare($sql);
        if ($params) {
            foreach ($params as $value) {
                $req->bindParam($value[0], Caracter::normalise_ChaineDeCaracteres($value[1]), $value[2]);
            }
        }
        $req->execute();
        //$req->closeCursor();
        return $req;
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
}
