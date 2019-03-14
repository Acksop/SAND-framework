<?php

namespace MVC\Classe;

class Bdd
{
    public $bdd;

    public function __construct($bdd = 'bdd1')
    {
        switch($bdd) {
            case 'bdd1':
                $this->bdd = new PDO(DSN_BDD1, USER_BDD1, PASS_BDD1);
            break;
            case 'bdd2':
                $this->bdd = new PDO(DSN_BDD2, USER_BDD2, PASS_BDD2);
            break;
            default:
                $this->bdd = new PDO(DSN_FICHES, USER_FICHES, PASS_FICHES);
        }
    }

    public function faireUneRequete($sql)
    {
        $req = $this->bdd->query($sql, PDO::FETCH_ASSOC);
        return $req;
    }

    public function creerTableau($res){

        $tab = array();
        foreach($res as $key => $row){
            $tab[$key] = $row;
        }
        return $tab;
    }
}