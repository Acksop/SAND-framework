<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/symfony/admin", name="admin")
     */
    public function adminAction()
    {
        // replace this example code with whatever you need
        return $this->render('default/page.html.twig', [
            'text' => 'admin',
        ]);
    }
}
