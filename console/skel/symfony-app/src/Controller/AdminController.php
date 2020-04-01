<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin-test/admin", name="admin")
     */
    public function adminAction()
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'admin',
        ]);
    }
}
