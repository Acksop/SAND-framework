<?php

class Bdd
{
    public $bdd;

    public function __construct()
    {
        $this->bdd = new PDO(DSN, USER, PASS);
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