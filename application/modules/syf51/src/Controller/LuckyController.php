<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LuckyController extends AbstractController
{
    /**
     * @Route("/syf51", name="homepage")
     */
    public function indexAction(Request $request)
    {
        print_r("<pre>");
        print_r($this->get('session'));
        print_r($_COOKIE);
        print_r($_SESSION);
        $_SESSION['test-user51'] = "user51";
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'homepage',
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