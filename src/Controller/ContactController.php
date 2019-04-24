<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/02/19
 * Time: 21:22
 */

namespace App\Controller;


use App\Form\ContactType;
use App\Service\EmailManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
    * @Route("/contact",name="app_index_contact", methods={"GET"})
    */
    public function contact(Request $request, EmailManager $emailManager)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $emailManager->sendContactForm($data);

            return $this->redirectToRoute('app_index_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
