<?php

/* breadcrumb.twig */
class __TwigTemplate_94f1b51e7eff2386c25d22dcf9bb9fb7e88e2c911b9c5da3887f495406fe3b9d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'extra' => array($this, 'block_extra'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<ul class=\"breadcrumb\">
    <li><a href=\"";
        // line 2
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("tree", array("repo" => ($context["repo"] ?? null), "commitishPath" => ($context["branch"] ?? null))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
        echo "</a></li>
    ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["breadcrumbs"] ?? null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 4
            echo "        <span class=\"divider\">/</span>
        <li";
            // line 5
            if ($this->getAttribute($context["loop"], "last", array())) {
                echo " class=\"active\"";
            }
            echo ">";
            if ( !$this->getAttribute($context["loop"], "last", array())) {
                echo "<a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("tree", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . $this->getAttribute($context["breadcrumb"], "path", array())))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["breadcrumb"], "dir", array()), "html", null, true);
                echo "</a>";
            }
            if ($this->getAttribute($context["loop"], "last", array())) {
                echo twig_escape_filter($this->env, $this->getAttribute($context["breadcrumb"], "dir", array()), "html", null, true);
            }
            echo "</li>
    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        echo "
    ";
        // line 8
        $this->displayBlock('extra', $context, $blocks);
        // line 9
        echo "</ul>
";
    }

    // line 8
    public function block_extra($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "breadcrumb.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 8,  83 => 9,  81 => 8,  78 => 7,  49 => 5,  46 => 4,  29 => 3,  23 => 2,  20 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "breadcrumb.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/breadcrumb.twig");
    }
}
