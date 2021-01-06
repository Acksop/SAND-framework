<?php

/* commits_list.twig */
class __TwigTemplate_83b91dc6b9474e28a3d067df760e7d1aaff54e6e7fd03c0d455b404713cd4305 extends Twig_Template
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
        if (($context["commits"] ?? null)) {
            // line 2
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["commits"] ?? null));
            foreach ($context['_seq'] as $context["date"] => $context["commit"]) {
                // line 3
                echo "<table class=\"table table-striped table-bordered\">
    <thead>
        <tr>
            <th colspan=\"3\">";
                // line 6
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $context["date"], "F j, Y"), "html", null, true);
                echo "</th>
        </tr>
    </thead>
    <tbody>
        ";
                // line 10
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["commit"]);
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 11
                    echo "        <tr>
            <td width=\"5%\"><img src=\"";
                    // line 12
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('avatar')->getCallable(), array($this->getAttribute($this->getAttribute($context["item"], "author", array()), "email", array()), 40)), "html", null, true);
                    echo "\" /></td>
            <td width=\"95%\">
                <span class=\"pull-right\"><a class=\"btn btn-small\" href=\"";
                    // line 14
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("commit", array("repo" => ($context["repo"] ?? null), "commit" => $this->getAttribute($context["item"], "hash", array()))), "html", null, true);
                    echo "\"><i class=\"icon-list-alt\"></i> View ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "shortHash", array()), "html", null, true);
                    echo "</a></span>
                <h4>";
                    // line 15
                    echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "message", array()), "html", null, true);
                    echo "</h4>
                <span>
                    <a href=\"mailto:";
                    // line 17
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "author", array()), "email", array()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "author", array()), "name", array()), "html", null, true);
                    echo "</a> authored on ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), array($this->getAttribute($context["item"], "date", array()))), "html", null, true);
                    echo "
                    ";
                    // line 18
                    if (($this->getAttribute($this->getAttribute($context["item"], "author", array()), "email", array()) != $this->getAttribute($this->getAttribute($context["item"], "commiter", array()), "email", array()))) {
                        // line 19
                        echo "                    &bull; <a href=\"mailto:";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "commiter", array()), "email", array()), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "commiter", array()), "name", array()), "html", null, true);
                        echo "</a> committed on ";
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), array($this->getAttribute($context["item"], "commiterDate", array()))), "html", null, true);
                        echo "
                    ";
                    }
                    // line 21
                    echo "                </span>
            </td>
        </tr>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 25
                echo "    </tbody>
</table>
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['date'], $context['commit'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } else {
            // line 29
            echo "<p>No results found.</p>
";
        }
        // line 31
        echo "
";
        // line 32
        if ((($context["page"] ?? null) != "searchcommits")) {
            // line 33
            echo "<ul class=\"pager\">
    ";
            // line 34
            if (($this->getAttribute(($context["pager"] ?? null), "current", array()) != 0)) {
                // line 35
                echo "    <li class=\"previous\">
        <a href=\"?page=";
                // line 36
                echo twig_escape_filter($this->env, $this->getAttribute(($context["pager"] ?? null), "previous", array()), "html", null, true);
                echo "\">&larr; Newer</a>
    </li>
    ";
            }
            // line 39
            echo "    ";
            if (($this->getAttribute(($context["pager"] ?? null), "current", array()) != $this->getAttribute(($context["pager"] ?? null), "last", array()))) {
                // line 40
                echo "    <li class=\"next\">
        <a href=\"?page=";
                // line 41
                echo twig_escape_filter($this->env, $this->getAttribute(($context["pager"] ?? null), "next", array()), "html", null, true);
                echo "\">Older &rarr;</a>
    </li>
    ";
            }
            // line 44
            echo "</ul>
";
        }
    }

    public function getTemplateName()
    {
        return "commits_list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 44,  127 => 41,  124 => 40,  121 => 39,  115 => 36,  112 => 35,  110 => 34,  107 => 33,  105 => 32,  102 => 31,  98 => 29,  89 => 25,  80 => 21,  70 => 19,  68 => 18,  60 => 17,  55 => 15,  49 => 14,  44 => 12,  41 => 11,  37 => 10,  30 => 6,  25 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "commits_list.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/commits_list.twig");
    }
}
