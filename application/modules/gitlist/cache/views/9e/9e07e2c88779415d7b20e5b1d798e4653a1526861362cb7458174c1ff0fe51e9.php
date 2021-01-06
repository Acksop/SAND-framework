<?php

/* error.twig */
class __TwigTemplate_95cfad686402070ce62cd54c41698adb703af2cb3e0d3090b63b8e751e96d80a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "error.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
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

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "GitList";
    }

    // line 4
    public function block_body($context, array $blocks = array())
    {
        // line 5
        $this->loadTemplate("navigation.twig", "error.twig", 5)->display($context);
        // line 6
        echo "
<div class=\"container\">

    <div class=\"alert alert-error\">
        <strong>Oops!</strong> ";
        // line 10
        echo twig_escape_filter($this->env, ($context["message"] ?? null), "html", null, true);
        echo "
    </div>

    <hr />

    ";
        // line 15
        $this->loadTemplate("footer.twig", "error.twig", 15)->display($context);
        // line 16
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "error.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 16,  54 => 15,  46 => 10,  40 => 6,  38 => 5,  35 => 4,  29 => 2,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "error.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/error.twig");
    }
}
