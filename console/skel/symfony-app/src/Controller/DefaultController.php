<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/symfony", name="homepage")
     */
    public function indexAction()
    {
    	print_r("<pre>");
    	//print_r($this->get('session'));
        print_r($_COOKIE);
        print_r($_SESSION);
        print_r("</pre>");
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'homepage',
        ]);
    }
    
    /**
     * @Route("/symfony/page1", name="page1")
     */
    public function page1Action()
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page1',
        ]);
    }
    /**
     * @Route("/symfony/page2", name="page2")
     */
    public function page2Action()
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page2',
        ]);
    }
}
