<?php

/**
  * Class RsaAttributes
  *
  * @package Besancon\AuthBundle\Security\Getters
  * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
  *
  */

namespace  App\Besancon\AuthBundle\Security\Getters;

use  App\Besancon\AuthBundle\Security\Interfaces\AttributesInterface;

/**
  * Class RsaAttributes
  *
  * Cette classe permet d'accèder aux informations (entête HTTP) de l'utilisateur
  * renvoyé par RSA CT à partir des méthodes d'accès définies dans l'interface AttributesInterface
  *
  */
class RsaAttributes implements AttributesInterface
{
    public function getCompletName()
    {
        return (isset($_SERVER['HTTP_CN'])) ? $_SERVER['HTTP_CN'] : null;
    }

    public function getDiscipline()
    {
        return (isset($_SERVER['HTTP_DISCIPLINE'])) ? $_SERVER['HTTP_DISCIPLINE'] : null;
    }

    public function getFonctM()
    {
        return (isset($_SERVER['HTTP_FONCTM'])) ? $_SERVER['HTTP_FONCTM'] : null;
    }

    public function getRne()
    {
        return (isset($_SERVER['HTTP_RNE'])) ? $_SERVER['HTTP_FREDURNE'] : null;
    }

    public function getFreDuRne()
    {
        return (isset($_SERVER['HTTP_FREDURNE'])) ? explode(',', $_SERVER['HTTP_FREDURNE']) : null;
    }

    public function getFreDuRneResp()
    {
        return (isset($_SERVER['HTTP_FREDURNERESP'])) ? explode(',', $_SERVER['HTTP_FREDURNERESP']) : null;
    }

    public function getMail()
    {
        return (isset($_SERVER['HTTP_CTEMAIL'])) ? $_SERVER['HTTP_CTEMAIL'] : null;
    }

    public function getTitle()
    {
        return (isset($_SERVER['HTTP_TITLE'])) ? $_SERVER['HTTP_TITLE'] : null;
    }

    public function getUsername()
    {
        return (isset($_SERVER['HTTP_CT_REMOTE_USER'])) ? $_SERVER['HTTP_CT_REMOTE_USER'] : null;
    }

    public function getFrEduResDel()
    {
        return (isset($_SERVER['HTTP_FREDURESDEL'])) ? $_SERVER['HTTP_FREDURESDEL'] : null;
    }
    
    public function getFrEduFonctAdm()
    {
        return (isset($_SERVER['HTTP_FREDUFONCTADM'])) ? $_SERVER['HTTP_FREDUFONCTADM'] : null;
    }

    public function getFirstName()
    {
        return (isset($_SERVER['HTTP_CTFN'])) ? $_SERVER['HTTP_CTFN'] : null;
    }

    public function getName()
    {
        return (isset($_SERVER['HTTP_CTLN'])) ? $_SERVER['HTTP_CTLN'] : null;
    }
    
    public function getGrade()
    {
        return (isset($_SERVER['HTTP_GRADE'])) ? $_SERVER['HTTP_GRADE'] : null;
    }
}
