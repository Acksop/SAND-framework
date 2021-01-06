<?php

/* menu.twig */
class __TwigTemplate_2fe34b06c775d908ab4007c2c25def8a967bdb86f49ba151a84f63931ebecda5 extends Twig_Template
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
        echo "<ul class=\"nav nav-tabs\">
    <li";
        // line 2
        if ((($context["page"] ?? null) == "files")) {
            echo " class=\"active\"";
        }
        echo "><a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("branch", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null))), "html", null, true);
        echo "\">Files</a></li>
    <li";
        // line 3
        if (twig_in_filter(($context["page"] ?? null), array(0 => "commits", 1 => "searchcommits"))) {
            echo " class=\"active\"";
        }
        echo "><a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("commits", array("repo" => ($context["repo"] ?? null), "commitishPath" => ($context["branch"] ?? null))), "html", null, true);
        echo "\">Commits</a></li>
    <li";
        // line 4
        if ((($context["page"] ?? null) == "stats")) {
            echo " class=\"active\"";
        }
        echo "><a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("stats", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null))), "html", null, true);
        echo "\">Stats</a></li>
  \t<li";
        // line 5
        if ((($context["page"] ?? null) == "network")) {
            echo " class=\"active\"";
        }
        echo "><a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("network", array("repo" => ($context["repo"] ?? null), "commitishPath" => ($context["branch"] ?? null))), "html", null, true);
        echo "\">Network</a></li>
  \t<li";
        // line 6
        if ((($context["page"] ?? null) == "treegraph")) {
            echo " class=\"active\"";
        }
        echo "><a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("treegraph", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null))), "html", null, true);
        echo "\">Graph</a></li>
</ul>
";
    }

    public function getTemplateName()
    {
        return "menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  54 => 6,  46 => 5,  38 => 4,  30 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "menu.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/menu.twig");
    }
}
