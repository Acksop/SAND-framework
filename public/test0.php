<?php
/**
 * Fichier d'exemple pour une
 * fonction statique dans une
 * classe
 * 
 * @category Application
 * @package Main
 * @copyright Moi 2012
 * @author Moi <moi@mail.com>
 * @license	Proprio http://monsite.com/license
 * @version Release 1 (2012-03-28)
 * 
 * @param unknown_type $test 
 */

/**
 * Classe qui ne sert à rien
 * @category Application
 * @package Main
 * @copyright Moi 2012
 * @author Moi <moi@mail.com>
 * @license	Proprio http://monsite.com/license
 * @version Release 1 (2012-03-28)
 * 
 * @param unknown_type $test 
 */
class Main
{
    
    /**
     * Fonction qui renvoiie l'inverse de son
     * paramètre
     * @param boolean $test
     * @return boolean
     */
    function test ($test = false)
    {
        return !$test;
    } 
}
