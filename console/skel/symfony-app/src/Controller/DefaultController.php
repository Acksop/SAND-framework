<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/admin-test", name="homepage")
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
     * @Route("/admin-test/page1", name="page1")
     */
    public function page1Action()
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page1',
        ]);
    }
    /**
     * @Route("/admin-test/page2", name="page2")
     */
    public function page2Action()
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page2',
        ]);
    }
}
