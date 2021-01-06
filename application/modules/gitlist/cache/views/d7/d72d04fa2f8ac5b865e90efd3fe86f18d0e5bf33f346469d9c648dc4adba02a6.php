<?php

/* layout_page.twig */
class __TwigTemplate_4878bce68b5076bafc60af71d3deda2ec60bc7ce16d9610ff1a4f67606b1a168 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "layout_page.twig", 1);
        $this->blocks = array(
            'body' => array($this, 'block_body'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->loadTemplate("navigation.twig", "layout_page.twig", 4)->display($context);
        // line 5
        echo "
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"span12\">
                ";
        // line 9
        if (twig_in_filter(($context["page"] ?? null), array(0 => "commits", 1 => "searchcommits"))) {
            // line 10
            echo "                <form class=\"form-search pull-right\" action=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
            echo "/";
            echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
            echo "/commits/";
            echo twig_escape_filter($this->env, ($context["branch"] ?? null), "html", null, true);
            echo "/search\" method=\"POST\">
                    <input type=\"text\" name=\"query\" class=\"input-medium search-query\" placeholder=\"Search commits...\" value=\"";
            // line 11
            echo twig_escape_filter($this->env, ((array_key_exists("query", $context)) ? (_twig_default_filter(($context["query"] ?? null), "")) : ("")), "html", null, true);
            echo "\">
                </form>
                ";
        } else {
            // line 14
            echo "                <form class=\"form-search pull-right\" action=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "basepath", array()), "html", null, true);
            echo "/";
            echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
            echo "/tree/";
            echo twig_escape_filter($this->env, ($context["branch"] ?? null), "html", null, true);
            echo "/search\" method=\"POST\">
                    <input type=\"text\" name=\"query\" class=\"input-medium search-query\" placeholder=\"Search tree...\" value=\"";
            // line 15
            echo twig_escape_filter($this->env, ((array_key_exists("query", $context)) ? (_twig_default_filter(($context["query"] ?? null), "")) : ("")), "html", null, true);
            echo "\">
                </form>
                ";
        }
        // line 18
        echo "
                ";
        // line 19
        if (array_key_exists("branches", $context)) {
            // line 20
            echo "                    ";
            $this->loadTemplate("branch_menu.twig", "layout_page.twig", 20)->display($context);
            // line 21
            echo "                ";
        }
        // line 22
        echo "
                ";
        // line 23
        $this->loadTemplate("menu.twig", "layout_page.twig", 23)->display($context);
        // line 24
        echo "            </div>
        </div>

        ";
        // line 27
        $this->displayBlock('content', $context, $blocks);
        // line 28
        echo "
        ";
        // line 29
        $this->loadTemplate("footer.twig", "layout_page.twig", 29)->display($context);
        // line 30
        echo "    </div>
";
    }

    // line 27
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout_page.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  106 => 27,  101 => 30,  99 => 29,  96 => 28,  94 => 27,  89 => 24,  87 => 23,  84 => 22,  81 => 21,  78 => 20,  76 => 19,  73 => 18,  67 => 15,  58 => 14,  52 => 11,  43 => 10,  41 => 9,  35 => 5,  32 => 4,  29 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout_page.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/layout_page.twig");
    }
}
