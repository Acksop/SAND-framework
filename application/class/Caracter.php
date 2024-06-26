<?php


namespace MVC\Classe;

use ForceUTF8\Encoding;

class Caracter
{
    public static function normalise_ChaineDeCaracteres($chaine)
    {
        return Encoding::fixUTF8(Caracter::fp_stripslashes($chaine));
    }

    public static function fp_stripslashes($str)
    {
        /*if (get_magic_quotes_gpc() == 1) {
            return stripslashes($str);
        } else {*/
            return $str;
        /*}*/
    }

    public static function normalise_ChaineDeCaracteresUpload($chaine)
    {
        return Caracter::fp_addslashes(Encoding::fixUTF8($chaine));
    }

    public static function fp_addslashes($str)
    {
        /*if (get_magic_quotes_gpc() == 1) {
            return $str;
        } else {*/
            return addslashes($str);
        /*}*/
    }

    public static function remplacerAccents($chaine)
    {
        $chaine = str_replace('é', '&eacute;', $chaine);
        $chaine = str_replace('è', '&egrave;', $chaine);
        $chaine = str_replace('ë', '&euml;', $chaine);
        $chaine = str_replace('ê', '&ecirc;', $chaine);
        $chaine = str_replace('ç', '&ccedil;', $chaine);
        $chaine = str_replace('Ç', '&Ccedil;', $chaine);
        $chaine = str_replace('à', '&agrave;', $chaine);
        // $chaine = str_replace('','&aeacute;',$chaine);
        $chaine = str_replace('â', '&circ;', $chaine);
        $chaine = str_replace('ä', '&uml;', $chaine);
        $chaine = str_replace('î', '&icirc;', $chaine);
        $chaine = str_replace('ï', '&iuml;', $chaine);
        $chaine = str_replace('ù', '&ugrave;', $chaine);
        $chaine = str_replace('û', '&ucirc;', $chaine);
        $chaine = str_replace('ü', '&uuml;', $chaine);
        $chaine = str_replace('É', '&Eacute;', $chaine);
        $chaine = str_replace('Ê', '&Ecirc;', $chaine);
        $chaine = str_replace('È', '&Egrave;', $chaine);
        $chaine = str_replace('Ë', '&Euml;', $chaine);
        $chaine = str_replace('À', '&Agrave;', $chaine);
        // $chaine = str_replace('','&Aeacute;',$chaine);
        $chaine = str_replace('Â', '&Acirc;', $chaine);
        $chaine = str_replace('Ä', '&Auml;', $chaine);
        $chaine = str_replace('Î', '&Icirc;', $chaine);
        $chaine = str_replace('Ï', '&Iuml;', $chaine);
        $chaine = str_replace('Ù', '&Ugrave;', $chaine);
        $chaine = str_replace('Û', '&Ucirc;', $chaine);
        $chaine = str_replace('Ü', '&Uuml;', $chaine);
        return Caracter::remplacerGuillemets($chaine);
    }

    public static function remplacerGuillemets($chaine)
    {
        $chaine = str_replace("'", "&#39;", $chaine);
        $chaine = str_replace('"', '&#34;', $chaine);
        return $chaine;
    }
    public static function avoid_sql_injection($chaine){
        $chaine = preg_replace("/`;--/", "", $chaine);
        $chaine = preg_replace("/';--/", "", $chaine);
        $chaine = preg_replace('/";--/', "", $chaine);
        $chaine = preg_replace("/;--/", "", $chaine);
        return $chaine;
    }
    public static function avoid_guillemets($chaine)
    {
        $chaine = str_replace("'", "", $chaine);
        $chaine = str_replace('"', '', $chaine);
        return $chaine;
    }
    public static function mettreEnMajusculeAccents($chaine, $trueAccent = false)
    {
        if (!$trueAccent) {
            $chaine = str_replace('é', 'E', $chaine);
            $chaine = str_replace('è', 'E', $chaine);
            $chaine = str_replace('ë', 'E', $chaine);
            $chaine = str_replace('ê', 'E', $chaine);
            $chaine = str_replace('ç', 'C', $chaine);
            $chaine = str_replace('Ç', 'C', $chaine);
            $chaine = str_replace('à', 'A', $chaine);
            // $chaine = str_replace('','&aeacute;',$chaine);
            $chaine = str_replace('â', 'A', $chaine);
            $chaine = str_replace('ä', 'A', $chaine);
            $chaine = str_replace('î', 'I', $chaine);
            $chaine = str_replace('ï', 'I', $chaine);
            $chaine = str_replace('ù', 'U', $chaine);
            $chaine = str_replace('û', 'U', $chaine);
            $chaine = str_replace('ü', 'U', $chaine);
        } else {
            $chaine = str_replace('é', 'É', $chaine);
            $chaine = str_replace('è', 'È', $chaine);
            $chaine = str_replace('ë', 'Ë', $chaine);
            $chaine = str_replace('ê', 'Ê', $chaine);
            $chaine = str_replace('ç', 'Ç', $chaine);
            $chaine = str_replace('Ç', 'Ç', $chaine);
            $chaine = str_replace('à', 'À', $chaine);
            // $chaine = str_replace('','&aeacute;',$chaine);
            $chaine = str_replace('â', 'Â', $chaine);
            $chaine = str_replace('ä', 'Ä', $chaine);
            $chaine = str_replace('î', 'Î', $chaine);
            $chaine = str_replace('ï', 'Ï', $chaine);
            $chaine = str_replace('ù', 'Ù', $chaine);
            $chaine = str_replace('û', 'Û', $chaine);
            $chaine = str_replace('ü', 'Ü', $chaine);
        }
        return $chaine;
    }
}
