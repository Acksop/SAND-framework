<?php

/* layout.twig */
class __TwigTemplate_18478d8044bf2883aca4c702a8e4bd139f045132d1f5f35cc527e29fda868b78 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\" />
        <title>";
        // line 5
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        if (($context["title"] ?? null)) {
            echo " - ";
        }
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/css/style.css\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/css/gitgraph.css\">
        <link rel=\"shortcut icon\" type=\"image/png\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/img/favicon.png\" />
        <!--[if lt IE 9]>
        <script type=\"application/javascript\" src=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/html5.js\"></script>
        <![endif]-->
    </head>

    <body>
        ";
        // line 15
        $this->displayBlock('body', $context, $blocks);
        // line 16
        echo "        ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 29
        echo "    </body>
</html>
";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo "Welcome!";
    }

    // line 15
    public function block_body($context, array $blocks = array())
    {
    }

    // line 16
    public function block_javascripts($context, array $blocks = array())
    {
        // line 17
        echo "        <script type=\"application/javascript\" src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/jquery.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/raphael.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/bootstrap.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/codemirror.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/showdown.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/table.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/list.min.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/main.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/networkGraph.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 26
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/gitgraph.js\"></script>
        <script type=\"application/javascript\" src=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
        echo "/themes/";
        echo twig_escape_filter($this->env, ($context["theme"] ?? null), "html", null, true);
        echo "/js/draw.js\"></script>
        ";
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  151 => 27,  145 => 26,  139 => 25,  133 => 24,  127 => 23,  121 => 22,  115 => 21,  109 => 20,  103 => 19,  97 => 18,  90 => 17,  87 => 16,  82 => 15,  76 => 5,  70 => 29,  67 => 16,  65 => 15,  55 => 10,  48 => 8,  42 => 7,  36 => 6,  28 => 5,  22 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/layout.twig");
    }
}
