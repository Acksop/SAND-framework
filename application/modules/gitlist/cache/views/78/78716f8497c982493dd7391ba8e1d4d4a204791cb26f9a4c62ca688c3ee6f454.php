<?php

/* stats.twig */
class __TwigTemplate_98543b80a47af36b1586e69a451be3632a35de4ef0b1e97de8a7933e9a7137a2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout_page.twig", "stats.twig", 1);
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
        $context["page"] = "stats";
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
        $this->loadTemplate("breadcrumb.twig", "stats.twig", 8)->display(array_merge($context, array("breadcrumbs" => array(0 => array("dir" => "Statistics", "path" => "")))));
        // line 9
        echo "
    <table class=\"table table-striped table-bordered\">
        <thead>
            <tr>
                <th width=\"30%\">File extensions (";
        // line 13
        echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute(($context["stats"] ?? null), "extensions", array())), "html", null, true);
        echo ")</th>
                <th width=\"40%\">Authors (";
        // line 14
        echo twig_escape_filter($this->env, twig_length_filter($this->env, ($context["authors"] ?? null)), "html", null, true);
        echo ")</th>
                <th width=\"30%\">Other</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                    ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["stats"] ?? null), "extensions", array()));
        foreach ($context['_seq'] as $context["ext"] => $context["amount"]) {
            // line 23
            echo "                        <li><strong>";
            echo twig_escape_filter($this->env, $context["ext"], "html", null, true);
            echo "</strong>: ";
            echo twig_escape_filter($this->env, $context["amount"], "html", null, true);
            echo " files</li>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['ext'], $context['amount'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "                    </ul>
                </td>
                <td>
                    <ul>
                    ";
        // line 29
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["authors"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["author"]) {
            // line 30
            echo "                        <li><strong><a href=\"mailto:";
            echo twig_escape_filter($this->env, $this->getAttribute($context["author"], "email", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["author"], "name", array()), "html", null, true);
            echo "</a></strong>: ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["author"], "commits", array()), "html", null, true);
            echo " commits</li>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['author'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "                    </ul>
                </td>
                <td>
                    <p>
                        <strong>Total files:</strong> ";
        // line 36
        echo twig_escape_filter($this->env, $this->getAttribute(($context["stats"] ?? null), "files", array()), "html", null, true);
        echo "
                    </p>

                    <p>
                        <strong>Total bytes:</strong> ";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute(($context["stats"] ?? null), "size", array()), "html", null, true);
        echo " bytes (";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_size')->getCallable(), array($this->getAttribute(($context["stats"] ?? null), "size", array()))), "html", null, true);
        echo ")
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr />
";
    }

    public function getTemplateName()
    {
        return "stats.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 40,  109 => 36,  103 => 32,  90 => 30,  86 => 29,  80 => 25,  69 => 23,  65 => 22,  54 => 14,  50 => 13,  44 => 9,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "stats.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/stats.twig");
    }
}
