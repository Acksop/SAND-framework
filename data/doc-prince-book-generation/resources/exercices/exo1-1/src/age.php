<?php
/**
 * usage:
 *      php age.php --birthdate=1986-07-06 --today=2013-07-06
 * 
 */
$options = getopt('', array('birthdate:', 'today::'));
$options['today'] = isset($options['today']) ? $options['today'] : date('Y-m-d');



$birthDate = new DateTime($options['birthdate']);
$today = new DateTime($options['today']);
$interval = $birthDate->diff($today);



if ($interval->invert == 1) {
    //
    // today < birthdate
    echo "Vous n'êtes pas encore né.";
} else {
    //
    // age
    echo sprintf('Vous avez %d ans. ', $interval->format('%y'));

    //
    // Happy birthday !
    if (($birthDate->format('%d') == $today->format('%d'))
            && ($birthDate->format('%m') == $today->format('%m'))) {
        echo ' Joyeux anniversaire !';
    }
}
