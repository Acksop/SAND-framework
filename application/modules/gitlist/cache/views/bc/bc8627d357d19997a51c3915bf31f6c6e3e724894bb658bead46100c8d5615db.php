<?php

/* tree.twig */
class __TwigTemplate_74e1d3d532bff3ca08812970328e2a26187fedd0631d80c6181c6ca30bfc6a4d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout_page.twig", "tree.twig", 1);
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
        $this->loadTemplate("tree.twig", "tree.twig", 8, "1604538170")->display(array_merge($context, array("breadcrumbs" => ($context["breadcrumbs"] ?? null))));
        // line 43
        echo "
    ";
        // line 44
        if ( !twig_test_empty(($context["files"] ?? null))) {
            // line 45
            echo "    <table class=\"tree\">
        <thead>
            <tr>
                <th width=\"80%\">name</th>
                <th width=\"10%\">mode</th>
                <th width=\"10%\">size</th>
            </tr>
        </thead>
        <tbody>
            ";
            // line 54
            if ( !(null === ($context["parent"] ?? null))) {
                // line 55
                echo "            <tr>
                <td><i class=\"icon-spaced\"></i>
                    ";
                // line 57
                if ( !($context["parent"] ?? null)) {
                    // line 58
                    echo "                        <a href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("branch", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null))), "html", null, true);
                    echo "\">..</a>
                    ";
                } else {
                    // line 60
                    echo "                        <a href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("tree", array("repo" => ($context["repo"] ?? null), "commitishPath" => ((($context["branch"] ?? null) . "/") . ($context["parent"] ?? null)))), "html", null, true);
                    echo "\">..</a>
                    ";
                }
                // line 62
                echo "                </td>
                <td></td>
                <td></td>
            </tr>
            ";
            }
            // line 67
            echo "            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["files"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
                // line 68
                echo "            <tr>
                <td><i class=\"";
                // line 69
                echo (((($this->getAttribute($context["file"], "type", array()) == "folder") || ($this->getAttribute($context["file"], "type", array()) == "symlink"))) ? ("icon-folder-open") : ("icon-file"));
                echo " icon-spaced\"></i> <a href=\"";
                // line 70
                if ((($this->getAttribute($context["file"], "type", array()) == "folder") || ($this->getAttribute($context["file"], "type", array()) == "symlink"))) {
                    // line 71
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("tree", array("repo" => ($context["repo"] ?? null), "commitishPath" => (((($context["branch"] ?? null) . "/") . ($context["path"] ?? null)) . ((($this->getAttribute($context["file"], "type", array()) == "symlink")) ? ($this->getAttribute($context["file"], "path", array())) : ($this->getAttribute($context["file"], "name", array())))))), "html", null, true);
                } else {
                    // line 73
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("blob", array("repo" => ($context["repo"] ?? null), "commitishPath" => (((($context["branch"] ?? null) . "/") . ($context["path"] ?? null)) . ((($this->getAttribute($context["file"], "type", array()) == "symlink")) ? ($this->getAttribute($context["file"], "path", array())) : ($this->getAttribute($context["file"], "name", array())))))), "html", null, true);
                }
                // line 75
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "name", array()), "html", null, true);
                echo "</a></td>
                <td>";
                // line 76
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "mode", array()), "html", null, true);
                echo "</td>
                <td>";
                // line 77
                if ($this->getAttribute($context["file"], "size", array())) {
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_size')->getCallable(), array($this->getAttribute($context["file"], "size", array()))), "html", null, true);
                }
                echo "</td>
            </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 80
            echo "        </tbody>
    </table>
    ";
        } else {
            // line 83
            echo "        <p>This repository is empty.</p>
    ";
        }
        // line 85
        echo "    ";
        if ((array_key_exists("readme", $context) &&  !twig_test_empty(($context["readme"] ?? null)))) {
            // line 86
            echo "        <div class=\"readme-view\">
            <div class=\"md-header\">
                <div class=\"meta\">";
            // line 88
            echo twig_escape_filter($this->env, $this->getAttribute(($context["readme"] ?? null), "filename", array()), "html", null, true);
            echo "</div>
            </div>
            <div id=\"md-content\">";
            // line 90
            echo twig_escape_filter($this->env, $this->getAttribute(($context["readme"] ?? null), "content", array()), "html", null, true);
            echo "</div>
        </div>
    ";
        }
        // line 93
        echo "
    <hr />
";
    }

    public function getTemplateName()
    {
        return "tree.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 93,  147 => 90,  142 => 88,  138 => 86,  135 => 85,  131 => 83,  126 => 80,  115 => 77,  111 => 76,  106 => 75,  103 => 73,  100 => 71,  98 => 70,  95 => 69,  92 => 68,  87 => 67,  80 => 62,  74 => 60,  68 => 58,  66 => 57,  62 => 55,  60 => 54,  49 => 45,  47 => 44,  44 => 43,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "tree.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/tree.twig");
    }
}


/* tree.twig */
class __TwigTemplate_74e1d3d532bff3ca08812970328e2a26187fedd0631d80c6181c6ca30bfc6a4d_1604538170 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 8
        $this->parent = $this->loadTemplate("breadcrumb.twig", "tree.twig", 8);
        $this->blocks = array(
            'extra' => array($this, 'block_extra'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "breadcrumb.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 9
    public function block_extra($context, array $blocks = array())
    {
        // line 10
        echo "            <div class=\"pull-right\">
                <div class=\"btn-group download-buttons\">
                    ";
        // line 12
        if ((($context["show_http_remote"] ?? null) || ($context["show_ssh_remote"] ?? null))) {
            // line 13
            echo "                    <a href=\"#\" class=\"btn btn-mini\" title=\"Show remotes to clone this repository.\" id=\"clone-button-show\">Clone</a>
                    ";
        }
        // line 15
        echo "                    <a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("archive", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null), "format" => "zip")), "html", null, true);
        echo "\" class=\"btn btn-mini\" title=\"Download '";
        echo twig_escape_filter($this->env, ($context["branch"] ?? null), "html", null, true);
        echo "' as a ZIP archive\">ZIP</a>
                    <a href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("archive", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null), "format" => "tar")), "html", null, true);
        echo "\" class=\"btn btn-mini\" title=\"Download '";
        echo twig_escape_filter($this->env, ($context["branch"] ?? null), "html", null, true);
        echo "' as a TAR archive\">TAR</a>
                </div>
                <a href=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("rss", array("repo" => ($context["repo"] ?? null), "branch" => ($context["branch"] ?? null))), "html", null, true);
        echo "\" class=\"rss-icon\"><i class=\"rss\"></i></a>
            </div>
            ";
        // line 20
        if ((($context["show_http_remote"] ?? null) || ($context["show_ssh_remote"] ?? null))) {
            // line 21
            echo "            <div id=\"clone-popup\">
                <div id=\"clone-popup-inner-wrapper\">
                    <a class=\"close\" href=\"#\" id=\"clone-button-hide\">&times;</a>
                    <div class=\"btn-group\">
                        ";
            // line 25
            if (($context["show_ssh_remote"] ?? null)) {
                // line 26
                echo "                        <button class=\"btn";
                echo (((($context["show_ssh_remote"] ?? null) && ($context["show_http_remote"] ?? null))) ? (" active") : (""));
                echo "\" id=\"clone-button-ssh\">SSH</button>
                        ";
            }
            // line 28
            echo "                        ";
            if (($context["show_http_remote"] ?? null)) {
                // line 29
                echo "                        <button class=\"btn\" id=\"clone-button-http\">HTTP";
                echo ((($context["use_https"] ?? null)) ? ("S") : (""));
                echo "</button>
                        ";
            }
            // line 31
            echo "                    </div><br />
                    ";
            // line 32
            if (($context["show_ssh_remote"] ?? null)) {
                // line 33
                echo "                    <input type=\"text\" class=\"form-control";
                echo ((($context["show_ssh_remote"] ?? null)) ? (" visible") : (""));
                echo "\" id=\"clone-input-ssh\" value=\"git clone ";
                echo ((($context["ssh_port"] ?? null)) ? ("ssh://") : (""));
                echo twig_escape_filter($this->env, twig_urlencode_filter(($context["ssh_user"] ?? null)), "html", null, true);
                echo ((($context["ssh_user"] ?? null)) ? ("@") : (""));
                echo twig_escape_filter($this->env, ((($context["ssh_host"] ?? null)) ? (($context["ssh_host"] ?? null)) : ($this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "host", array()))), "html", null, true);
                echo ":";
                echo twig_escape_filter($this->env, ((($context["ssh_port"] ?? null)) ? ((($context["ssh_port"] ?? null) . "/")) : ("")), "html", null, true);
                echo twig_escape_filter($this->env, ($context["ssh_url_subdir"] ?? null), "html", null, true);
                echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
                echo "\">
                    ";
            }
            // line 35
            echo "                    ";
            if (($context["show_http_remote"] ?? null)) {
                // line 36
                echo "                    <input type=\"text\" class=\"form-control";
                echo (((twig_test_empty(($context["show_ssh_remote"] ?? null)) && ($context["show_http_remote"] ?? null))) ? (" visible") : (""));
                echo "\" id=\"clone-input-http\" value=\"git clone http";
                echo ((($context["use_https"] ?? null)) ? ("s") : (""));
                echo "://";
                echo twig_escape_filter($this->env, twig_urlencode_filter(($context["http_user"] ?? null)), "html", null, true);
                echo ((($context["http_user"] ?? null)) ? ("@") : (""));
                echo twig_escape_filter($this->env, ((($context["http_host"] ?? null)) ? (($context["http_host"] ?? null)) : ($this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", array()), "host", array()))), "html", null, true);
                echo "/";
                echo twig_escape_filter($this->env, ($context["http_url_subdir"] ?? null), "html", null, true);
                echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
                echo "\">
                    ";
            }
            // line 38
            echo "                </div>
            </div>
            ";
        }
        // line 41
        echo "        ";
    }

    public function getTemplateName()
    {
        return "tree.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  313 => 41,  308 => 38,  293 => 36,  290 => 35,  275 => 33,  273 => 32,  270 => 31,  264 => 29,  261 => 28,  255 => 26,  253 => 25,  247 => 21,  245 => 20,  240 => 18,  233 => 16,  226 => 15,  222 => 13,  220 => 12,  216 => 10,  213 => 9,  196 => 8,  153 => 93,  147 => 90,  142 => 88,  138 => 86,  135 => 85,  131 => 83,  126 => 80,  115 => 77,  111 => 76,  106 => 75,  103 => 73,  100 => 71,  98 => 70,  95 => 69,  92 => 68,  87 => 67,  80 => 62,  74 => 60,  68 => 58,  66 => 57,  62 => 55,  60 => 54,  49 => 45,  47 => 44,  44 => 43,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "tree.twig", "/var/www/SAND-FrameWork-1.1.0/application/modules/gitlist/themes/default/twig/tree.twig");
    }
}
