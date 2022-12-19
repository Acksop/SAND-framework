<?php


namespace SAND\Classe;

class Asynchonous
{
    private $_css;
    private $_body_css;
    private $_header_javascript;
    private $_footer_javascript;

    public function __construct()
    {
        $this->_css = "";
        $this->_body_css = "";
        $this->_header_javascript = "";
        $this->_footer_javascript = "";
    }

    public function addCss($code)
    {
        $this->_css .= "\n";
        $this->_css .= $code;
    }
    public function addBodyCss($code)
    {
        $this->_body_css .= "\n";
        $this->_body_css .= $code;
    }
    public function addHeaderJs($code)
    {
        $this->_header_javascript .= "\n";
        $this->_header_javascript .= $code;
    }
    public function addFooterJs($code)
    {
        $this->_footer_javascript .= "\n";
        $this->_footer_javascript .= $code;
    }

    public function printCss()
    {
        echo $this->_css;
    }

    public function printBodyCss()
    {
        echo $this->_body_css;
    }

    public function printHeaderJs()
    {
        echo $this->_header_javascript;
    }

    public function printFooterJs()
    {
        echo $this->_footer_javascript;
    }
}
