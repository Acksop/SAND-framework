<?php

/* file.twig */
class __TwigTemplate_2b0ac13b81d1086760101f17fe559eb3e5d28b7f26b849c078abe7ec971367dc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout_page.twig", "file.twig", 1);
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
        $context["page"] = "files";
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
        $this->loadTemplate("breadcrumb.twig", "file.twig", 8)->display(array_merge($context, array("breadcrumbs" => ($context["breadcrumbs"] ?? null))));
        // line 9
        echo "
    <div class=\"source-view\">
        <div class=\"source-header\">
            <div class=\"meta\"></div>

            <div class=\"btn-group pull-right\">
                <a href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("blob_raw", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . ($context["file"] ?? null)))), "html", null, true);
        echo "\" class=\"btn btn-small\"><i class=\"icon-file\"></i> Raw</a>
                <a href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("blame", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . ($context["file"] ?? null)))), "html", null, true);
        echo "\" class=\"btn btn-small\"><i class=\"icon-bullhorn\"></i> Blame</a>
                <a href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("logpatch", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . ($context["file"] ?? null)))), "html", null, true);
        echo "\" class=\"btn btn-small\"><i class=\"icon-calendar\"></i> Patch Log</a>
                <a href=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("commits", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . ($context["file"] ?? null)))), "html", null, true);
        echo "\" class=\"btn btn-small\"><i class=\"icon-list-alt\"></i> History</a>
            </div>
        </div>
        ";
        // line 21
        if ((($context["fileType"] ?? null) == "image")) {
            // line 22
            echo "        <center><img src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("blob_raw", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . ($context["file"] ?? null)))), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, ($context["file"] ?? null), "html", null, true);
            echo "\" class=\"image-blob\" /></center>

        ";
        } elseif ((        // line 24
($context["fileType"] ?? null) == "markdown")) {
            // line 25
            echo "        <div class=\"md-view\"><div id=\"md-content\">";
            echo twig_escape_filter($this->env, ($context["blob"] ?? null), "html", null, true);
            echo "</div></div>

        ";
        } else {
            // line 28
            echo "        <pre id=\"sourcecode\" language=\"";
            echo twig_escape_filter($this->env, ($context["fileType"] ?? null), "html", null, true);
            echo "\">";
            echo htmlentities(($context["blob"] ?? null));
            echo "</pre>
        ";
        }
        // line 30
        echo "    </div>

    <hr />
";
    }

    public function getTemplateName()
    {
        return "file.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 30,  89 => 28,  82 => 25,  80 => 24,  72 => 22,  70 => 21,  64 => 18,  60 => 17,  56 => 16,  52 => 15,  44 => 9,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "file.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/file.twig");
    }
}
