<?php

use MVC\Classe\HttpMethodRequete;
use MVC\Classe\Implement\Action;
use MVC\Classe\Response;
use MVC\Classe\Url;

class DefaultAction extends Action
{
    public function default($data)
    {
        /**your action algorythm**/
        if (isset($data[0])) {
            $var1 = $data[0];
        } else {
            $var1 = 1;
        }
        if (isset($data[1])) {
            $var2 = $data[1];
        } else {
            $var2 = 2;
        }
        if (isset($data[2])) {
            $var3 = $data[2];
        } else {
            $var3 = 3;
        }

        return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3));
    }

    public function variableSlug($data)
    {

        /**your action algorythm**/
        if (isset($data[0])) {
            $var1 = $data[0];
        } else {
            $var1 = 1;
        }
        if (isset($data[1])) {
            $var2 = $data[1];
        } else {
            $var2 = 2;
        }
        if (isset($data[2])) {
            $var3 = $data[2];
        } else {
            $var3 = 3;
        }

        return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3));
    }

    public function makeHttp11($data)
    {

        $data = array('myval' => 25);
        //Dumper::dump($data);
        \MVC\Classe\Logger::addLog('action', 'http11 make request');
        $request = new HttpMethodRequete();
        $request->setUrl(Url::absolute_link_rewrite(false, 'accueil', ['var10' => 'val10']))->get($data);
        $request->setUrl(Url::absolute_link_rewrite(false, 'accueil', ['var10' => 'val10']))->post($data);
        $request->setUrl(Url::absolute_link_rewrite(false, 'accueil', ['var10' => 'val10']))->put($data);
        $request->setUrl(Url::absolute_link_rewrite(false, 'accueil', ['var10' => 'val10']))->delete($data);
    }
}
