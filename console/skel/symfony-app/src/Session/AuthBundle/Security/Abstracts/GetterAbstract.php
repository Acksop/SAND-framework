<?php

/**
 * Abstract class GetterAbstract
 * 
 * @package  Besancon\AuthBundle\Security\Abstracts
 * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
 */

namespace App\Session\AuthBundle\Security\Abstracts;

/**
 * Description of GetterAbstract
 *
 * @author belhadjali
 */
abstract class GetterAbstract {

    public function isACP(){
        return $this->getFrEduFonctAdm() == "ACP";
    }
    
    public function isDIR(){
        return $this->getFrEduFonctAdm() == "DIR";
    }
    
    public function isDEC(){
        return $this->getFrEduFonctAdm() == "DEC";
    }
    
    public function isDIR1D(){
        return $this->isDEC();
    }

    public function isIEN1D (){
        return $this->getFrEduFonctAdm() == "IEN1D";
    }
    
    public function isDIO(){
        return $this->getFrEduFonctAdm() == "IEN1D";
    }
}
