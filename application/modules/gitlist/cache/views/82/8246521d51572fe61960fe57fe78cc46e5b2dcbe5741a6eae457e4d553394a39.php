<?php

/* rss.twig */
class __TwigTemplate_be4459a381a3a1331de1ea737c9f1263db58cab8b3ee9a8dbf74d5375cfe781f extends Twig_Template
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
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\">
    <channel>
        <title>Latest commits in ";
        // line 4
        echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
        echo ":";
        echo twig_escape_filter($this->env, ($context["branch"] ?? null), "html", null, true);
        echo "</title>
        <description>RSS provided by GitList</description>
        <link>";
        // line 6
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getUrl("homepage");
        echo "</link>

        ";
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["commits"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["commit"]) {
            // line 9
            echo "        <item>
            <title>";
            // line 10
            echo twig_escape_filter($this->env, $this->getAttribute($context["commit"], "message", array()), "html", null, true);
            echo "</title>
            <description>";
            // line 11
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["commit"], "author", array()), "name", array()), "html", null, true);
            echo " authored ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["commit"], "shortHash", array()), "html", null, true);
            echo " in ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), array($this->getAttribute($context["commit"], "date", array()))), "html", null, true);
            echo "</description>
            <link>";
            // line 12
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getUrl("commit", array("repo" => ($context["repo"] ?? null), "commit" => $this->getAttribute($context["commit"], "hash", array()))), "html", null, true);
            echo "</link>
            <pubDate>";
            // line 13
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($context["commit"], "date", array()), "r"), "html", null, true);
            echo "</pubDate>
            <author>";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["commit"], "author", array()), "email", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["commit"], "author", array()), "name", array()), "html", null, true);
            echo ")</author>
        </item>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['commit'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "    </channel>
</rss>
";
    }

    public function getTemplateName()
    {
        return "rss.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 17,  63 => 14,  59 => 13,  55 => 12,  47 => 11,  43 => 10,  40 => 9,  36 => 8,  31 => 6,  24 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "rss.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/rss.twig");
    }
}
