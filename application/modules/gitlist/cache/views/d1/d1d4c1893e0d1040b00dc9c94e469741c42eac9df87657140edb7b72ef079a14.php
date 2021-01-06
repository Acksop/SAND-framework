<?php

/* footer.twig */
class __TwigTemplate_5029b81fb673022f28d476632b8acef6ef39efd54ec1c8340f2021b7f2899998 extends Twig_Template
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
        echo "<footer>
    <p>Powered by <a href=\"https://github.com/klaussilveira/gitlist\">GitList 1.0.2</a></p>
</footer>
";
    }

    public function getTemplateName()
    {
        return "footer.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "footer.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/footer.twig");
    }
}
