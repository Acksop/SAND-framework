<?php

declare(strict_types=1);

namespace Tests;

use MVC\Classe\Caracter;
use PHPUnit\Framework\TestCase;

/**
 * Exemple de test sur un cas simple
 * @package Tests
 */
final class StrToUpperTest extends TestCase
{
    public function testCaracteres(): void
    {
        $string = array(
            "pere",
            "pére",
            "père",
            "paîres",
            "noël",
        );
        $string_value_upper = array();
        $string_upper = array(
            "PERE",
            "PERE",
            "PERE",
            "PAIRES",
            "NOEL",
        );
        foreach($string as $value){
            //$string_value_upper[] = Caracter::mettreEnMajusculeAccents(strtoupper($value),true);
            $string_value_upper[] = strtoupper($value);
        }
        for($i=0;$i<count($string_upper);$i++){
            $this->assertEquals($string_value_upper[$i], $string_upper[$i]);
        }
    }
}
