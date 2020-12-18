<?php

use Michelf\MarkdownExtra;
use MVC\Classe\Implement\Conduit;

class DocConduit extends Conduit
{
    // Route('/docs')
    public function index()
    {

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

        return $this->render('docs', array('page_title' => 'Foo', 'description' => 'DocConduit', 'files' => $files));
    }

    // Route('/docs/file/{name}')
    public function readfile()
    {

        $markdown = file_get_contents(DATA_PATH . '/docs/' . $this->name);

        $my_html = MarkdownExtra::defaultTransform($markdown);

        return $this->render('docs', array('page_title' => 'Foo', 'description' => 'DocConduit', 'data' => $my_html));

    }
}