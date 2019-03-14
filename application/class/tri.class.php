<?php
/**
 * Created by PhpStorm.
 * User: roy
 * Date: 05/03/2019
 * Time: 16:08
 */
class Tri{

    public static function cmp($a,$b){
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}