<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class DefaultController extends Controller
{
    /**
     * @Route("/syf43", name="homepage")
     */
    public function indexAction(Request $request)
    {
        print_r("<pre>");
        //print_r($this->get('session'));
        print_r($_COOKIE);
        //print_r($_SESSION);
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'homepage',
        ]);
    }

    /**
     * @Route("/syf43/page1", name="page1")
     */
    public function page1Action(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page1',
        ]);
    }
    /**
     * @Route("/syf43/page2", name="page2")
     */
    public function page2Action(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'page2',
        ]);
    }
}