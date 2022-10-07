<?php

namespace SAND\Classe;

class Tri
{
    public static function cmp($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}
