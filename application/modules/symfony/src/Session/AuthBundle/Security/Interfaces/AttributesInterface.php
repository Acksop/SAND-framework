<?php
/**
  * Interface AttributesInterface
  *
  * @package Besancon\AuthBundle\Security\Interfaces
  * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
  *
  */

namespace App\Session\AuthBundle\Security\Interfaces;

/**
  * Interface AttributesInterface
  *
  */
interface AttributesInterface
{

  const NO_VALUE = "X";

  const FREDURNE_OFFSET_RNE = 0;
  const FREDURNE_OFFSET_SECTEUR = 2;
  const FREDURNE_OFFSET_FONCTION_EXERCICE = 3;
  const FREDURNE_OFFSET_FONCTION_RNEUAJ = 4;
  const FREDURNE_OFFSET_1CODETNA = 5; // 1er chiffre code nature nomenclature 
  const FREDURNE_OFFSET_CODETTY = 6; // code type etablissement nomenclature 
  const FREDURNE_OFFSET_CODETNA = 7; // code nature etablissement nomenclature 



  const FREDURNERESP_OFFSET_RNE = 0;
  const FREDURNERESP_OFFSET_SECTEUR = 2; //PU ou PR
  const FREDURNERESP_OFFSET_AFFECTATION = 3; // A pour Affectation anticipé N pour affectation normale F pour affectation qui fini le 31/08
  const FREDURNERESP_OFFSET_1CODETNA = 4; // 1er chiffre code nature nomenclature 
  const FREDURNERESP_OFFSET_CODETTY = 5; // code type etablissement nomenclature 
  const FREDURNERESP_OFFSET_CODETNA = 6; // code nature nomenclature 


  const TYPE_LYCEE_GENERAL = "LYC";
  const TYPE_LYCEE_PRO = "LP";
  const TYPE_COLLEGE = "CLG";
  const TYPE_SEGPA = "SES";

  const CODE_NATURE_RECTORAT = ["802"];
  const CODE_NATURE_DSDEN = ["806"];
  const CODE_NATURE_INSPECTION = ["809"];
  const CODE_NATURE_LYCEE_GENERAL_ET_TECHNO = ["300"];
  const CODE_NATURE_LYCEE_TECHNO = ["301"];
  const CODE_NATURE_LYCEE_GENERAL = ["302", "306"];
  const CODE_NATURE_LYCEE_AGRICOLE = ["307"];
  const CODE_NATURE_LYCEE_PRO = ["320"];
  const CODE_NATURE_COLLEGE = ["340"];
  const CODE_COLLEGE_NATURE_SPE = ["352"];
  const CODE_NATURE_SEGPA = ["390"];


  const GRADES_IEN = ["1152", "1151"];

  const GRADES_RECTEUR = ["0201"];
  const GRADES_SG = ["0211", "0911", "0912"];
  const GRADES_ASG = ["0981"];

  const GRADES_DASEN = ["0921", "0922"];
  const GRADES_ADJOINT_DASEN = ["0971"];

  const CODES_DISCIPLINE_ASH = ["N0006"];
  const CODES_DISCIPLINE_DIR = ["D0010"];
  const CODES_DISCIPLINE_ADJOINT_DIR = ["D0011"];
  /**
    * Renvoie le prénom de l'agent
    *
    * Correspond au champ "givenName" du LDAP
    *
    * @return string|null 
    * prénom de l'agent
    */
  public function getFirstName();

  /**
    * Renvoie l'identifiant LDAP de l'agent
    *
    * Correspond au champ "uid" du LDAP
    *
    * @return string|null 
    * uid de l'agent
    */
  public function getUsername();

  /**
    * Renvoie le nom de famille de l'agent
    *
    * Correspond au champ "sn" du LDAP
    *
    * @return string|null 
    * nom de l'agent
    */
  public function getName();

  /**
    * Renvoie l'adresse mail de l'agent
    *
    * Correspond au champ "mail" du LDAP
    *
    * @return string|null 
    * adresse mail de l'agent
    */
  public function getMail();

  /**
    * Renvoie le nom complet de l'agent
    *
    * Correspond au champ "cn" du LDAP
    *
    * @return string|null 
    * nom complete de l'agent
    */
  public function getCompletName();

  /**
    * Renvoie le title de l'agent
    *
    * Correspond au champ "title" du LDAP
    *
    * @return string|null 
    * title de l'agent
    */
  public function getTitle();

  /**
    * Renvoie le code discipline de l'agent
    *
    * Correspond au champ "discipline" du LDAP
    *
    * @return string|null 
    * code discipline de l'agent
    */
  public function getDiscipline();

  /**
     * Renvoie l'établissements d'affectation de l'agent
     *
     * Correspond au champ "rne" du LDAP
     *
     * @return string|null 
     * * établissement d'affectation de l'agent
     */
  public function getRne();

  /**
    * Renvoie l'établissements d’exercice de l'agent
    *
    * Correspond au champ "FreDuRne" du LDAP
    *
    * @return array|null 
    * établissement(s) d'exercice de l'agent
    */
  public function getFreDuRne();

  /**
    * Renvoie le(s) établissement(s) en responsabilité de l'agent
    *
    * Correspond au champ "FreDuRneResp" du LDAP
    *
    * @return array|null 
    * établissement(s) en responsabalité de l'agent
    */
  public function getFreDuRneResp();

  /**
    * Renvoie le(s) déléguation(s)/attribution(s) de l'agent ouvrant des droits d'accès
    * à une ressource d'une application pour un ou des rne
    *
    * Correspond au champ "FreDuRneDel" du LDAP
    *
    * @return array|null 
    * déléguation(s)/attribution(s) de l'agent
    */
  public function getFrEduResDel();

  /**
    * Renvoie la fonction administrative de l'agent 
    * correspondant à un profil particulier
    *
    * Correspond au champ "FrEduFonctAdm" du LDAP
    *
    * @return string|null 
    * fonction administrative de l'agent 
    */
  public function getFrEduFonctAdm();

  /**
    * Renvoie la fonction de l'agent 
    * Attention :   initialisé à la création de la fiche avec la même valeur que l’attribut fonction.
    *               Puis, par l’application Annuaire, l’agent peut le modifier.
    *
    * Correspond au champ "fonctm" du LDAP
    *
    * @return string|null 
    * fonction de l'agent 
    */
  public function getFonctM();

  /**
    * Renvoie le grade de l'agent 
    * Alimenté à partir de la valeur agt.gradco
    * Se référer à la base des nomenclatures dans la table N_GRADE pour voir
    * les correspondances : http://infocentre.pleiade.education.fr/bcn/workspace/viewTable/n/N_GRADE
    *
    * Correspond au champ "grade" du LDAP
    *
    * @return string|null 
    * fonction de l'agent 
    */
  public function getGrade();
}
