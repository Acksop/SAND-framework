<?php

/* navigation.twig */
class __TwigTemplate_9ec705f6c96d081246fe5f344823a71a135d1524e81d18a7a98066049099767f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"navbar navbar-scroll-top\">
    <div class=\"navbar-inner\">
        <div class=\"container\">
            <a class=\"btn btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
            </a>
            <a class=\"brand\" href=\"";
        // line 9
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("homepage");
        echo "\">";
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</a>
            <div class=\"nav-collapse\">
                <ul class=\"nav pull-right\">
                    <li><a href=\"http://gitlist.org/\">About</a></li>
                    <li><a href=\"";
        // line 13
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("homepage");
        echo "refresh\">Refresh</a></li>
                    <li><a href=\"https://github.com/klaussilveira/gitlist/issues/new\">Report bug</a></li>
                    <li><a href=\"https://github.com/klaussilveira/gitlist/wiki\">Help</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "navigation.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 13,  29 => 9,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "navigation.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/navigation.twig");
    }
}
