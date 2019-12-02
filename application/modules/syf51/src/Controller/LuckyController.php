<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LuckyController extends AbstractController
{

    function recursiveObjectToJson($object)
    {
        $json = "";
        var_dump($object);
        echo "Iterating over: " . $object->count() . " values\n";
        $iterator = $object->getIterator();
        while ($iterator->valid()) {
            print_r($iterator->key() . "=" . $iterator->current() . "\n");
            $iterator->next();
        }
        print_r($iterator);
        foreach ($iterator as $key => $value) {
            print_r($key . $value);
            if (is_object($value)) {
                $json .= '{ "text" : "' . $key . ' - ' . get_class($value) . '", "children" : [';
                $json .= $this->recursiveObjectToJson($value);
                $json .= ']}';
            } elseif (is_array($value)) {
                $json .= '{ "text" : "' . $key . '", "children" : [';
                $json .= $this->recursiveObjectToJson($value);
                $json .= ']}';
            } else {
                $json .= '{ "text" : "' . $value . '" }';
            }
        }
        return $json;
    }

    private function ObjectToJson($object)
    {
        $json = '{
            "core" : {
                "data" : [';
        $json .= $this->recursiveObjectToJson($object);
        $json .= ']}}';

        return $json;
    }

    /*it comes from https://www.php.net/manual/en/function.var-dump.php */
    public function dump_debug($input, $collapse = false)
    {
        $recursive = function ($data, $level = 0) use (&$recursive, $collapse) {
            global $argv;

            $isTerminal = isset($argv);

            if (!$isTerminal && $level == 0 && !defined("DUMP_DEBUG_SCRIPT")) {
                define("DUMP_DEBUG_SCRIPT", true);

                echo '<script language="Javascript">function toggleDisplay(id) {';
                echo 'var state = document.getElementById("container"+id).style.display;';
                echo 'document.getElementById("container"+id).style.display = state == "inline" ? "none" : "inline";';
                echo 'document.getElementById("plus"+id).style.display = state == "inline" ? "inline" : "none";';
                echo '}</script>' . "\n";
            }

            $type = !is_string($data) && is_callable($data) ? "Callable" : ucfirst(gettype($data));
            $type_data = null;
            $type_color = null;
            $type_length = null;

            switch ($type) {
                case "String":
                    $type_color = "green";
                    $type_length = strlen($data);
                    $type_data = "\"" . htmlentities($data) . "\"";
                    break;

                case "Double":
                case "Float":
                    $type = "Float";
                    $type_color = "#0099c5";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data);
                    break;

                case "Integer":
                    $type_color = "red";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data);
                    break;

                case "Boolean":
                    $type_color = "#92008d";
                    $type_length = strlen($data);
                    $type_data = $data ? "TRUE" : "FALSE";
                    break;

                case "NULL":
                    $type_length = 0;
                    break;

                case "Array":
                    $type_length = count($data);
            }

            if (in_array($type, array("Object", "Array"))) {
                $notEmpty = false;

                foreach ($data as $key => $value) {
                    if (!$notEmpty) {
                        $notEmpty = true;

                        if ($isTerminal) {
                            echo $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "\n";

                        } else {
                            $id = substr(md5(rand() . ":" . $key . ":" . $level), 0, 8);

                            echo "<a href=\"javascript:toggleDisplay('" . $id . "');\" style=\"text-decoration:none\">";
                            echo "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>";
                            echo "</a>";
                            echo "<span id=\"plus" . $id . "\" style=\"display: " . ($collapse ? "inline" : "none") . ";\">&nbsp;&#10549;</span>";
                            echo "<div id=\"container" . $id . "\" style=\"display: " . ($collapse ? "" : "inline") . ";\">";
                            echo "<br />";
                        }

                        for ($i = 0; $i <= $level; $i++) {
                            echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }

                        echo $isTerminal ? "\n" : "<br />";
                    }

                    for ($i = 0; $i <= $level; $i++) {
                        echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    echo $isTerminal ? "[" . $key . "] => " : "<span style='color:black'>[" . $key . "]&nbsp;=>&nbsp;</span>";

                    call_user_func($recursive, $value, $level + 1);
                }

                if ($notEmpty) {
                    for ($i = 0; $i <= $level; $i++) {
                        echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    if (!$isTerminal) {
                        echo "</div>";
                    }

                } else {
                    echo $isTerminal ?
                        $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " :
                        "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";
                }

            } else {
                echo $isTerminal ?
                    $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " :
                    "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";

                if ($type_data != null) {
                    echo $isTerminal ? $type_data : "<span style='color:" . $type_color . "'>" . $type_data . "</span>";
                }
            }

            echo $isTerminal ? "\n" : "<br />";
        };

        call_user_func($recursive, $input);
    }

    /**
     * @Route("/syf51", name="homepage")
     */
    public function indexAction(Request $request)
    {
        print_r("<pre>");
        $session = $this->var_log($this->get('session'));
        //$session = json_encode($this->get('session'));
        print_r($session);
        print_r($_COOKIE);
        print_r($_SESSION);
        print_r("</pre>");
        $_SESSION['test-user51'] = "user51";
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'homepage', 'json' => $session
        ]);
    }

    /**
     * @Route("/syf51/page1", name="page1")
     */
    public function page1Action(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page1',
        ]);
    }

    /**
     * @Route("/syf51/page2", name="page2")
     */
    public function page2Action(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page2',
        ]);
    }

    /**
     * @Route("/syf51/number")
     */
    public function number()
    {

        print_r("<pre>");
        print_r($this->get('session'));
        print_r($_COOKIE);
        print_r($_SESSION);

        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}