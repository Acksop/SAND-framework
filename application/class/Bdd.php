<?php

namespace SAND\Classe;

class Bdd
{
    public $bdd;

    public function __construct($bdd = 'default')
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
        $sql = \SAND\Classe\Caracter::avoid_sql_injection($sql);
        $req = $this->bdd->query($sql);
        // Print Pdo::ERRORs
        if (!$req && (ENV == 'TEST' || ENV == 'DEVEL')) {
            echo "\nPDO::errorInfo():\n";
            print_r($this->bdd->errorInfo());
            print_r($sql);
        }
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
        try{
            $req = $this->bdd->prepare($sql);
            // cas de tests des variables qui lance une exception
            //if(!$Allow) throw new Exception("Le format de l'isbn ne correspond pas au format attendu");
            if ($params) {
                foreach ($params as $value) {
                    $value[1] = Caracter::normalise_ChaineDeCaracteres($value[1]);
                    if($value[2] !== 'VALUE') {
                        $req->bindParam(':'.$value[0], $value[1], $value[2]);
                    }else{
                        $req->bindValue(':'.$value[0], $value[1]);
                    }
                }
            }
            $req->execute();

        }
        catch(PDOException $pdo_e){
            if (ENV == 'TEST' || ENV == 'DEVEL')
                {
                    // Print Pdo::ERRORs
                    //Faire quelque choses en cas d'erreur PDO
                echo "\nPDOException():\n";
                print_r($this->bdd->errorInfo());
                print_r($pdo_e);
                print_r($sql);


                }
        }
        catch(Exception $e){
            if (ENV == 'TEST' || ENV == 'DEVEL') {
                //Pour les autres erreurs faire autre chose
                echo "\nException():\n";
                print_r($this->bdd->errorInfo());
                print_r($e);
                print_r($sql);
            }
        }
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
