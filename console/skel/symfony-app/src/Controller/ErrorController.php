<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/symfony/unauthorized", name="unauthorized")
     */
    public function indexAction()
    {
        print_r("<pre>");
        //print_r($this->get('session'));
        print_r($_COOKIE);
        print_r($_SESSION);
        print_r("</pre>");
        // replace this example code with whatever you need
        return $this->render('default/unauthorized.html.twig', [

        ]);
    }
}
