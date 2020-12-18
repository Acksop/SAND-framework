<?php

namespace App\Session\AuthBundle\Security\Traits;

use App\Session\AuthBundle\Security\Interfaces\AttributesInterface;

trait ProfilsCalculator
{
    //est recteur
    public function isRecteur()
    {
        return in_array($this->ai->getDiscipline(), AttributesInterface::GRADES_RECTEUR);
    }

    //est secrétaire général d'académie
    public function isSG()
    {
        return in_array($this->ai->getDiscipline(), AttributesInterface::GRADES_SG);
    }

    //est adjoint au secrétaire général d'académie
    public function isASG()
    {
        return in_array($this->ai->getDiscipline(), AttributesInterface::GRADES_ASG);
    }

    //agent comptable
    public function isACP()
    {
        return $this->ai->getFrEduFonctAdm() == "ACP";
    }

    //enseignant
    public function isENS()
    {
        return $this->ai->getFrEduFonctAdm() == AttributesInterface::NO_VALUE && $this->ai->getTitle() == "ENS" && $this->ai->getFrEduRneResp() == AttributesInterface::NO_VALUE;
    }

    //agent issue d'AGAPE PRIVE
    public function isAgentPrive()
    {
        return $this->ai->getTypensi() == "R";
    }

    //equipe de direction établissement

    public function isDIR()
    {
        return $this->isGroupeDIR() && in_array($this->ai->getDiscipline(), AttributesInterface::CODES_DISCIPLINE_DIR);
    }

    //directeur 2nd degré

    public function isGroupeDIR()
    {
        return $this->ai->getFrEduFonctAdm() == "DIR";
    }

    //directeur adjoint 2nd degré

    public function isAdjointDIR()
    {
        return $this->isGroupeDIR() && in_array($this->ai->getDiscipline(), AttributesInterface::CODES_DISCIPLINE_ADJOINT_DIR);
    }

    //directeur d'ecole

    public function isDIR1D()
    {
        return $this->isDEC();
    }

    //alias directeur d'ecole

    public function isDEC()
    {
        return $this->ai->getFrEduFonctAdm() == "DEC";
    }

    //adaptation scolaire et de la scolarisation des élèves handicapé

    public function isIEN1D()
    {
        return $this->isIEN() && $this->ai->getFrEduFonctAdm() == "IEN1D";
    }

    //est inspecteur

    public function isIEN()
    {
        return (!is_null($this->ai->getGrade())) ? in_array($this->ai->getGrade(), AttributesInterface::GRADES_IEN) : $this->ai->getTitle() == "INS";
    }

    //est inspecteur 1er degré

    public function isIENASH()
    {
        return $this->isASH() && $this->isIEN();
    }

    //est inspecteur ASH

    public function isASH()
    {
        return in_array($this->ai->getDiscipline(), AttributesInterface::CODES_DISCIPLINE_ASH);
    }

    //est DASEN

    public function isDASEN()
    {
        return in_array($this->ai->getGrade(), AttributesInterface::GRADES_DASEN);
    }

    //est adjoint DASEN
    public function isAdjointDasen()
    {
        return in_array($this->ai->getGrade(), AttributesInterface::GRADES_ADJOINT_DASEN);
    }

    //est directeur CIO
    public function isDIO()
    {
        return $this->ai->getFrEduFonctAdm() == "DIO";
    }

    public function isAffectedToRectorat()
    {
        $result = $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_RECTORAT);
        return (count($result)) ? true : false;
    }

    public function filterFrEduRneByNature($nature)
    {
        if ($this->ai->getFrEduRne() == AttributesInterface::NO_VALUE) {
            return [];
        }
        $FrEduRne = (!is_array($this->ai->getFrEduRne())) ? [$this->ai->getFrEduRne()] : $this->ai->getFrEduRne();
        $uais = array_filter($FrEduRne, function ($value) use ($nature) {
            $arr_value = explode("$", $value);
            if (!is_array($arr_value) || !array_key_exists(AttributesInterface::FREDURNE_OFFSET_CODETNA, $arr_value)) {
                return false;
            }
            if (is_array($nature)) {
                return in_array($arr_value[AttributesInterface::FREDURNE_OFFSET_CODETNA], $nature);
            }
            return $arr_value[AttributesInterface::FREDURNE_OFFSET_CODETNA] == $nature;
        });

        return $uais;
    }

    public function isAffectedToDSDEN()
    {
        $result = $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_DSDEN);
        return (count($result)) ? true : false;
    }

    public function isAffectedToLYC()
    {
        $result = $this->filterFrEduRneByType(AttributesInterface::TYPE_LYCEE_GENERAL);
        return (count($result)) ? true : false;
    }

    // public function hasLYC()
    // {
    //     return $this->findUaiRespByType(AttributesInterface::TYPE_LYCEE_GENERAL);
    // }

    // public function hasLYCP()
    // {
    //     return $this->findUaiRespByType(AttributesInterface::TYPE_LYCEE_PRO);
    // }

    public function filterFrEduRneByType($type)
    {
        if ($this->ai->getFrEduRne() == AttributesInterface::NO_VALUE) {
            return [];
        }
        $FrEduRne = (!is_array($this->ai->getFrEduRne())) ? [$this->ai->getFrEduRne()] : $this->ai->getFrEduRne();

        $uais = array_filter($FrEduRne, function ($value) use ($type) {
            $arr_value = explode("$", $value);
            if (!is_array($arr_value) || !array_key_exists(AttributesInterface::FREDURNE_OFFSET_CODETTY, $arr_value)) {
                return false;
            }
            if (is_array($type)) {
                return in_array($arr_value[AttributesInterface::FREDURNE_OFFSET_CODETTY], $type);
            }
            return $arr_value[AttributesInterface::FREDURNE_OFFSET_CODETTY] == $type;
        });

        return $uais;
    }

    public function isAffectedToLP()
    {
        $result = $this->filterFrEduRneByType(AttributesInterface::TYPE_LYCEE_PRO);
        return (count($result)) ? true : false;
    }

    public function isAffectedToInspection()
    {
        $result = $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_INSPECTION);
        return (count($result)) ? true : false;
    }

    public function isAffectedToSEGPA()
    {
        $result = $this->filterFrEduRneByType(AttributesInterface::TYPE_SEGPA);
        return (count($result)) ? true : false;
    }

    public function isRespOfLYC()
    {
        $result = $this->filterFrEduRneRespByType(AttributesInterface::TYPE_LYCEE_GENERAL);
        return (count($result)) ? true : false;
    }

    public function filterFrEduRneRespByType($type)
    {
        if ($this->ai->getFrEduRneResp() == AttributesInterface::NO_VALUE) {
            return [];
        }
        $FrEduRneResp = (!is_array($this->ai->getFrEduRneResp())) ? [$this->ai->getFrEduRneResp()] : $this->ai->getFrEduRneResp();

        $uais = array_filter($FrEduRneResp, function ($value) use ($type) {
            $arr_value = explode("$", $value);
            if (!is_array($arr_value) || !array_key_exists(AttributesInterface::FREDURNERESP_OFFSET_CODETTY, $arr_value)) {
                return false;
            }
            if (is_array($type)) {
                return in_array($arr_value[AttributesInterface::FREDURNERESP_OFFSET_CODETTY], $type);
            }
            return $arr_value[AttributesInterface::FREDURNERESP_OFFSET_CODETTY] == $type;
        });

        return $uais;
    }

    public function isRespOfLP()
    {
        $result = $this->filterFrEduRneRespByType(AttributesInterface::TYPE_LYCEE_PRO);
        return (count($result)) ? true : false;
    }

    public function isRespOfSEGPA()
    {
        $result = $this->filterFrEduRneRespByType(AttributesInterface::TYPE_SEGPA);
        return (count($result)) ? true : false;
    }

    /****************************************************************************************
     *  Filtres sur FrEduRne
     ***************************************************************************************/

    public function filterFrEduRneByLYCG()
    {
        return $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_LYCEE_GENERAL);
    }

    public function filterFrEduRneByLYCGT()
    {
        return $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_LYCEE_GENERAL_ET_TECHNO);
    }

    public function filterFrEduRneByLP()
    {
        return $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_LYCEE_GENERAL_ET_TECHNO);
    }

    public function filterFrEduRneByCLG()
    {
        return $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_COLLEGE);
    }

    public function filterFrEduRneByLYCAG()
    {
        return $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_LYCEE_AGRICOLE);
    }

    public function filterFrEduRneBySEGPA()
    {
        return $this->filterFrEduRneByNature(AttributesInterface::CODE_NATURE_SEGPA);
    }

    /****************************************************************************************
     *  Filtres sur FrEduRneResp
     ***************************************************************************************/

    public function filterFrEduRneRespByLYCG()
    {
        return $this->filterFrEduRneRespByNature(AttributesInterface::CODE_NATURE_LYCEE_GENERAL);
    }

    public function filterFrEduRneRespByNature($nature)
    {
        if ($this->ai->getFrEduRneResp() == AttributesInterface::NO_VALUE) {
            return [];
        }
        $FrEduRneResp = (!is_array($this->ai->getFrEduRneResp())) ? [$this->ai->getFrEduRneResp()] : $this->ai->getFrEduRneResp();

        $uais = array_filter($FrEduRneResp, function ($value) use ($nature) {
            $arr_value = explode("$", $value);
            if (!is_array($arr_value) || !array_key_exists(AttributesInterface::FREDURNERESP_OFFSET_CODETNA, $arr_value)) {
                return false;
            }
            if (is_array($nature)) {
                return in_array($arr_value[AttributesInterface::FREDURNERESP_OFFSET_CODETNA], $nature);
            }
            return $arr_value[AttributesInterface::FREDURNERESP_OFFSET_CODETNA] == $nature;
        });

        return $uais;
    }

    public function filterFrEduRneRespByLYCGT()
    {
        return $this->filterFrEduRneRespByNature(AttributesInterface::CODE_NATURE_LYCEE_GENERAL_ET_TECHNO);
    }

    public function filterFrEduRneRespByLP()
    {
        return $this->filterFrEduRneRespByNature(AttributesInterface::CODE_NATURE_LYCEE_GENERAL_ET_TECHNO);
    }

    public function filterFrEduRneRespByCLG()
    {
        return $this->filterFrEduRneRespByNature(AttributesInterface::CODE_NATURE_COLLEGE);
    }

    public function filterFrEduRneRespByLYCAG()
    {
        return $this->filterFrEduRneRespByNature(AttributesInterface::CODE_NATURE_LYCEE_AGRICOLE);
    }

    public function filterFrEduRneRespBySEGPA()
    {
        return $this->filterFrEduRneRespByNature(AttributesInterface::CODE_NATURE_SEGPA);
    }
}
