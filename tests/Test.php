<?php
require_once '../public/scriptPHP/cookies.php';

class Test extends PHPUnit_Framework_TestCase
{
    //declaration de variables pour les fonctions de tests
    public $essai;

    //fonction constructeur du test (public)
    public function Test()
    {
    }

    //fonction de testing proprement dite
    public function testOne()
    {
        $essai = ajouterZero(1);

        $this->assertEquals('01', $essai);
        $this->assertNotEquals(10, $essai);
    }
}
