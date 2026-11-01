<?php


namespace SAND\Classe;

class Caracter
{
    /**
     * Caractère qui suit l'octet de tête d'un caractère UTF-8 lu à tort comme
     * du Windows-1252 : U+0080 à U+00BF, ou caractère Windows-1252 des octets
     * 0x80 à 0x9F (Œ, €, —, guillemets…).
     */
    private const SUITE_WINDOWS_1252 = '(?:[\x{80}-\x{BF}]|[\x{152}\x{153}\x{160}\x{161}\x{178}\x{17D}\x{17E}'
        . '\x{192}\x{2C6}\x{2DC}\x{2013}\x{2014}\x{2018}\x{2019}\x{201A}\x{201C}\x{201D}\x{201E}\x{2020}'
        . '\x{2021}\x{2022}\x{2026}\x{2030}\x{2039}\x{203A}\x{20AC}\x{2122}])';

    public static function normalise_ChaineDeCaracteres($chaine)
    {
        return Caracter::corrigerUtf8(Caracter::fp_stripslashes($chaine));
    }

    /**
     * Rend une chaîne en UTF-8 valide (remplace ForceUTF8\Encoding::fixUTF8(),
     * qui appelle utf8_decode(), dépréciée depuis PHP 8.2) :
     * - une chaîne qui n'est pas de l'UTF-8 valide est lue comme du
     *   Windows-1252 (sur-ensemble d'ISO-8859-1) ;
     * - un caractère encodé deux fois ou plus en UTF-8 (« Ã© ») est décodé
     *   jusqu'à stabilité (« é »).
     * Les tableaux sont traités récursivement ; les autres types sont rendus tels quels.
     */
    public static function corrigerUtf8($texte)
    {
        if (is_array($texte)) {
            return array_map([self::class, 'corrigerUtf8'], $texte);
        }
        if (!is_string($texte)) {
            return $texte;
        }
        if (!mb_check_encoding($texte, 'UTF-8')) {
            $texte = mb_convert_encoding($texte, 'UTF-8', 'Windows-1252');
        }

        $motif = '/[\x{C2}-\x{DF}]' . self::SUITE_WINDOWS_1252
            . '|[\x{E0}-\x{EF}]' . self::SUITE_WINDOWS_1252 . '{2}'
            . '|[\x{F0}-\x{F4}]' . self::SUITE_WINDOWS_1252 . '{3}/u';
        do {
            $precedent = $texte;
            $texte = preg_replace_callback($motif, [self::class, 'defaireDoubleEncodage'], $texte);
        } while ($texte !== $precedent);

        return $texte;
    }

    /**
     * Ramène une séquence suspecte à ses octets d'origine ; elle n'est
     * remplacée que si ces octets forment de l'UTF-8 valide.
     */
    private static function defaireDoubleEncodage(array $sequence): string
    {
        $octets = '';
        foreach (mb_str_split($sequence[0], 1, 'UTF-8') as $caractere) {
            $point = mb_ord($caractere, 'UTF-8');
            $octets .= $point <= 0xFF ? chr($point) : mb_convert_encoding($caractere, 'Windows-1252', 'UTF-8');
        }

        return mb_check_encoding($octets, 'UTF-8') ? $octets : $sequence[0];
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
        return Caracter::fp_addslashes(Caracter::corrigerUtf8($chaine));
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
