<?php

/* commits.twig */
class __TwigTemplate_3b7f14f45be10bcc46f8785e5b33af081ae4979750b132d5aea1849899a430cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout_page.twig", "commits.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout_page.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["page"] = "commits";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo "GitList";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        $this->loadTemplate("breadcrumb.twig", "commits.twig", 8)->display(array_merge($context, array("breadcrumbs" => array(0 => array("dir" => "Commit history", "path" => "")))));
        // line 9
        echo "
    ";
        // line 10
        $this->loadTemplate("commits_list.twig", "commits.twig", 10)->display($context);
        // line 11
        echo "
    <hr />
";
    }

    public function getTemplateName()
    {
        return "commits.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 11,  47 => 10,  44 => 9,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "commits.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/commits.twig");
    }
}
