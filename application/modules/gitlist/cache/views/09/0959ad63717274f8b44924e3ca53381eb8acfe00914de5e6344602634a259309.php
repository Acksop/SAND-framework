<?php

/* branch_menu.twig */
class __TwigTemplate_aca6c30a6759b6d7591ed76011bd578eac07d37d878fbf1ab9a2f5ca25959db7 extends Twig_Template
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
        echo "<div class=\"btn-group pull-left space-right\" id=\"branchList\">
    <button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">browsing: <strong>";
        // line 2
        echo twig_escape_filter($this->env, ($context["branch"] ?? null), "html", null, true);
        echo "</strong> <span class=\"caret\"></span></button>

    <div class=\"dropdown-menu\">
        <div class=\"search\">
            <input class=\"search\" placeholder=\"Filter branch/tags\" autofocus>
        </div>
    <ul class=\"unstyled list\">
    <li class=\"dropdown-header\">Branches</li>
    ";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["branches"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 11
            echo "        <li><a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("branch", array("repo" => ($context["repo"] ?? null), "branch" => $context["item"])), "html", null, true);
            echo "\"><span class=\"item\">";
            echo twig_escape_filter($this->env, $context["item"], "html", null, true);
            echo "</span></a></li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "    ";
        if (($context["tags"] ?? null)) {
            // line 14
            echo "    <li class=\"dropdown-header\">Tags</li>
    ";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["tags"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 16
                echo "        <li><a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("branch", array("repo" => ($context["repo"] ?? null), "branch" => $context["item"])), "html", null, true);
                echo "\"><span class=\"item\">";
                echo twig_escape_filter($this->env, $context["item"], "html", null, true);
                echo "</span></a></li>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 18
            echo "    ";
        }
        // line 19
        echo "    </ul>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "branch_menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 19,  69 => 18,  58 => 16,  54 => 15,  51 => 14,  48 => 13,  37 => 11,  33 => 10,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "branch_menu.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/branch_menu.twig");
    }
}
