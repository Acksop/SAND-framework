<?php


namespace MVC\Classe;


class Asynchonous
{

    private $_css;
    private $_javascript;

    public function __construct()
    {
        $this->_css = "";
        $this->_javascript = "";
    }

    public function addCss($code)
    {
        $this->_css .= "\n";
        $this->_css .= $code;
    }

    public function addJs($code)
    {
        $this->_javascript .= "\n";
        $this->_javascript .= $code;
    }

    public function printCss()
    {
        echo $this->_css;
    }

    public function printJs()
    {
        echo $this->_javascript;
    }

}