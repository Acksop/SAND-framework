<?php
/**
 * @package Besancon\AuthBundle\Security\Getters
 * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
 */

namespace App\Session\AuthBundle\Security\Getters;

use App\Session\AuthBundle\Security\Interfaces\AttributesInterface;

/**
 * Class CasAttributes
 *
 * Cette classe permet d'accèder aux informations (attributs) de l'utilisateur
 * renvoyé par CAS à partir des méthodes d'accès définies dans l'interface AttributesInterface
 *
 */
class CasAttributes implements AttributesInterface
{
    public function getFirstName()
    {
        return \phpCAS::getAttribute("prenom");
    }

    public function getCompletName()
    {
        return \phpCAS::getAttribute("nomcomplet");
    }

    public function getName()
    {
        return \phpCAS::getAttribute("nom");
    }

    public function getDiscipline()
    {
        return \phpCAS::getAttribute("discipline");
    }

    public function getFonctM()
    {
        return \phpCAS::getAttribute("fonctm");
    }

    public function getRne()
    {
        return \phpCAS::getAttribute("rne");
    }

    public function getFreDuRne()
    {
        return \phpCAS::getAttribute("FrEduRne");
    }

    public function getFreDuRneResp()
    {
        return \phpCAS::getAttribute("FrEduRneResp");
    }

    public function getMail()
    {
        return \phpCAS::getAttribute("mail");
    }

    public function getTitle()
    {
        return \phpCAS::getAttribute("title");
    }

    public function getUsername()
    {
        return \phpCAS::getUser();
    }

    public function getFrEduResDel()
    {
        return \phpCAS::getAttribute("FrEduResDel");
    }

    public function getFrEduFonctAdm()
    {
        return \phpCAS::getAttribute("FrEduFonctAdm");
    }

    public function getGrade()
    {
        return \phpCAS::getAttribute("grade");
    }
}
