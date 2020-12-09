<?php
/**
 * @package Besancon\AuthBundle\Security\Getters
 * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
 */

namespace  App\Session\AuthBundle\Security\Getters;

use  App\Session\AuthBundle\Security\Interfaces\AttributesInterface;

/**
 * Class CasAttributes
 *
 * Cette classe permet d'accèder aux informations (attributs) de l'utilisateur
 * renvoyé par CAS à partir des méthodes d'accès définies dans l'interface AttributesInterface
 *
 */
class SessionAttributes implements AttributesInterface {

    public function getFirstName() {
        return ;
    }

    public function getCompletName() {
        return ;
    }

    public function getName() {
        return ;
    }

    public function getDiscipline() {
        return ;
    }

    public function getFonctM() {
        return ;
    }

    public function getRne() {
        return ;
    }

    public function getFreDuRne() {
        return ;
    }

    public function getFreDuRneResp() {
        return ;
    }

    public function getMail() {
        return ;
    }

    public function getTitle() {
        return ;
    }

    public function getUsername() {
        return ;
    }

    public function getFrEduResDel(){
        return ;
    }

    public function getFrEduFonctAdm() {
        return ;
    }

    public function getGrade() {
        return ;
    }

}