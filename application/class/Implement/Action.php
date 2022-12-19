<?php


namespace SAND\Classe\Implement;

class Action
{
    public function render($view, $data)
    {
        return $this->renderBlade($view,$data);
    }

    public function renderJSON($json)
    {
        //header('Content-Type: application/json; charset=utf-8');
        return json_encode($json, JSON_HEX_APOS);
    }

    public function renderTwig($view, $data)
    {
        $paths = new \SplPriorityQueue;

        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "system", 100);
        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "layout", 200);
        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "view", 300);

        $renderer = new \Windwalker\Renderer\TwigRenderer($paths);
        $view .= '.html';

        return $renderer->render($view, $data);
    }

    public function renderBlade($view, $data)
    {
        $paths = new \SplPriorityQueue;

        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "system", 100);
        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "layout", 200);
        $paths->insert(VIEW_PATH . DIRECTORY_SEPARATOR . "view", 300);

        $renderer = new \Windwalker\Renderer\BladeRenderer($paths, array('cache_path' => VIEW_PATH . DIRECTORY_SEPARATOR . "cache"));

        return $renderer->render($view, $data);
    }
}
