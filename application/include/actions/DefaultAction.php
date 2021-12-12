<?php

class DefaultAction extends \MVC\Classe\Implement\Action
{
    public function default()
    {
        /**your action algorythm**/
        if (isset($data1)) {
            $var1 = $data1;
        } else {
            $var1 = 1;
        }
        if (isset($data2)) {
            $var2 = $data2;
        } else {
            $var2 = 2;
        }
        if (isset($data3)) {
            $var3 = $data3;
        } else {
            $var3 = 3;
        }

        return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3));
    }
    public function defaultBlade($data1,$data2,$data3)
    {
        /**your action algorythm**/
        if (isset($data1)) {
            $var1 = $data1;
        } else {
            $var1 = 1;
        }
        if (isset($data2)) {
            $var2 = $data2;
        } else {
            $var2 = 2;
        }
        if (isset($data3)) {
            $var3 = $data3;
        } else {
            $var3 = 3;
        }

        return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3),'blade');
    }
    public function defaultTwig($data1,$data2,$data3)
    {
        /**your action algorythm**/
        if (isset($data1)) {
            $var1 = $data1;
        } else {
            $var1 = 1;
        }
        if (isset($data2)) {
            $var2 = $data2;
        } else {
            $var2 = 2;
        }
        if (isset($data3)) {
            $var3 = $data3;
        } else {
            $var3 = 3;
        }

        return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3),'twig');
    }

    public function variableSlug($data1,$data2,$data3)
    {
        /**your action algorythm**/
        if (isset($data1)) {
            $var1 = $data1;
        } else {
            $var1 = 1;
        }
        if (isset($data2)) {
            $var2 = $data2;
        } else {
            $var2 = 2;
        }
        if (isset($data3)) {
            $var3 = $data3;
        } else {
            $var3 = 3;
        }

        return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3));
    }

    public function makeHttp11()
    {
        $data = array('myval' => 25);
        //Dumper::dump($data);
        \MVC\Classe\Logger::addLog('action', 'http11 make request');
        $request = new \MVC\Classe\HttpMethodRequete();
        $request->setUrl(\MVC\Classe\Url::absolute_link_rewrite(false, 'accueil', ['var10'=>'val10']))->get($data);
        $request->setUrl(\MVC\Classe\Url::absolute_link_rewrite(false, 'accueil', ['var10'=>'val10']))->post($data);
        $request->setUrl(\MVC\Classe\Url::absolute_link_rewrite(false, 'accueil', ['var10'=>'val10']))->put($data);
        $request->setUrl(\MVC\Classe\Url::absolute_link_rewrite(false, 'accueil', ['var10'=>'val10']))->delete($data);
    }
}
