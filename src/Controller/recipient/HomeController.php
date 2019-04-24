<?php

namespace App\Controller\recipient;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_RECIPIENT')")
     * @Route("/prestataire", name="recipient_index_home")
     */
    public function index()
    {
        return $this->render('recipient/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
