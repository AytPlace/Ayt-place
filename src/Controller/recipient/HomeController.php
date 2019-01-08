<?php

namespace App\Controller\recipient;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/prestataire", name="recipient_index_home")
     */
    public function index()
    {
        return $this->render('recipient/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}