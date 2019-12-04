<?php


namespace MVC\Classe;


use Symfony\Component\Validator\Constraints\Date;

class Logger
{

    static function addLog($type = 'default', $what = "")
    {
        $file = LOG_PATH . DIRECTORY_SEPARATOR . 'app.' . $type . '.log';
        $browser = new Browser();
        $date = date("F j, Y, g:i a");
        $what = PHP_EOL . '[' . $date . ' by ' . $browser->user . ']' . PHP_EOL . $browser->userAgent . PHP_EOL . $what;
        if (is_file($file)) {
            file_put_contents($file, PHP_EOL . $what, FILE_APPEND | LOCK_EX);
        } else {
            file_put_contents($file, $what);
        }
    }

}