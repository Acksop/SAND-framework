<?php


namespace MVC\Classe;

class Logger
{
    public static function addLog($type = 'default', $what = "")
    {
        $file = LOG_PATH . DIRECTORY_SEPARATOR . 'app.' . $type . '.log';
        $browser = new Browser();
        $date = date("F j, Y, g:i a");
        $what = PHP_EOL . '[' . $date . ' by ' . $browser->user . ']' . PHP_EOL . $browser->userAgent . PHP_EOL . $what;
        //$what = PHP_EOL . '[' . $date . ' by ]' . PHP_EOL . $what;
        if (is_file($file)) {
            file_put_contents($file, PHP_EOL . $what, FILE_APPEND | LOCK_EX);
        } else {
            file_put_contents($file, $what);
        }
    }

    /**
     * Fonction courante permettant de logger les erreurs obtenues dans un fichier
     *
     * @param array $errors
     * @return void
     */
    public static function logCommandErrors(array $errors)
    {
        // log connection errors to the web service
        ob_start();
        foreach ($errors as $key => $value) {
            echo "\n\n$key : \n";
            print_r($value);
        }
        $write_string = ob_get_clean();
        file_put_contents(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "errors_command.log", $write_string);

        return;
    }
}
