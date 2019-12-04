<?php


namespace MVC\Classe\Implement;


class Action
{
    public function render($view, $data)
    {

        //ob_start();

        $paths = new \SplPriorityQueue;

        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "system", 100);
        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "layout", 200);
        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "view", 300);

        $renderer = new \Windwalker\Renderer\BladeRenderer($paths, array('cache_path' => VIEW_PATH . DIRECTORY_SEPARATOR . "cache"));

        return $renderer->render($view, $data);

        //return ob_get_clean();

    }
}