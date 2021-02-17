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

        $this->templateData['page_title'] = 'Foo';
        $this->templateData['description'] = 'DocConduit';
        $this->templateData['files'] = $files;

        return $this->render('docs', $this->templateData);
    }

    // Route('/docs/file/{file}')
    public function readfile()
    {
        \MVC\Object\Session::createAndTestSession();
        $markdown = file_get_contents(DATA_PATH . '/docs/' . $this->file);

        $my_html = MarkdownExtra::defaultTransform($markdown);

        $this->templateData['page_title'] = 'Foo';
        $this->templateData['description'] = 'DocConduit';
        $this->templateData['data'] = $my_html;

        return $this->render('docs', $this->templateData);

    }
}
