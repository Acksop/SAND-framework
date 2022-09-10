<?php

use Michelf\MarkdownExtra;
use MVC\Classe\Implement\Conduit;

class DocConduit extends Conduit
{
    // Route('/docs')
    public function index()
    {
        \MVC\Object\Session::createAndTestSession();
        $files = array();

        if ($handle = opendir(DATA_PATH . '/docs')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    $files[] = $entry;
                }
            }

            closedir($handle);
        }

        asort($files);

        $this->templateData['page_title'] = 'Sommaire de la documentation du Framework';
        $this->templateData['description'] = 'Sommaire, Documentation, SAND, Framework';
        $this->templateData['files'] = $files;

        return $this->render('docs', $this->templateData);
    }

    // Route('/docs/file/{file}')
    public function readfile()
    {
        \MVC\Object\Session::createAndTestSession();

        $files = array();

        if ($handle = opendir(DATA_PATH . '/docs')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    $files[] = $entry;
                }
            }

            closedir($handle);
        }

        sort($files);

        $key_file = array_search($this->file,$files);

        $markdown = file_get_contents(DATA_PATH . '/docs/' . $this->file);

        $my_html = MarkdownExtra::defaultTransform($markdown);

        $this->templateData['page_title'] = 'Documentation du Framework';
        $this->templateData['description'] = 'Documentation, SAND, Framework';
        $this->templateData['data'] = $my_html;
        if(isset($files[$key_file - 1])) {
            $this->templateData['previous'] = $files[$key_file - 1];
        }
        if(isset($files[$key_file + 1])) {
            $this->templateData['next'] = $files[$key_file + 1];
        }

        return $this->render('docs', $this->templateData);

    }
}
