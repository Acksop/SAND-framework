<?php

include_once '../public/scriptPHP/objets/fichier.class.php';
include_once '../public/scriptPHP/cookies.php';

class FichierTest extends PHPUnit_Framework_TestCase
{
    protected $essai;

    public function testFichier()
    {
        $essai = ajouterZero(1);
        $this->assertEquals('10', $essai);
        $this->assertNotEquals(10, $essai);
    }
}
