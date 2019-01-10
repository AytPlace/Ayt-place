<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 10/01/19
 * Time: 20:39
 */

namespace App\Controller\recipient;

use App\Entity\Medium;
use App\Form\SirenType;
use App\Form\UpdateRecipientType;
use App\Repository\RecipientRepository;
use App\Service\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/prestataire/profil/", name="recipient_profil_home")
     */
    public function indexAction(Request $request, RecipientRepository $recipientRepository)
    {
        $recipient = $recipientRepository->find($this->getUser()->getId());
        $form = $this->createForm(UpdateRecipientType::class, $recipient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('recipient_profil_home');
        }

        return $this->render('recipient/profil/index.html.twig', [
            'form' => $form->createView(),
            'recipient' => $recipient
        ]);
    }

    /**
     * @Route("/prestataire/profil/justificatif", name="recipient_profil_add_siren")
     */
    public function addSirenAction(RecipientRepository $recipientRepository, Request $request, FileManager $fileManager)
    {
        $recipient = $recipientRepository->find($this->getUser()->getId());
        $form = $this->createForm(SirenType::class, $recipient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($form->get('sirenPicture') && $form->get('sirenPicture')->getData() != null)
            {
                $medium = new Medium();
                $medium->setUploadedFile($form->get('sirenPicture')->getData());
                $fileManager->upload($medium);
                $recipient->setSirenPicture($medium);

                $em->persist($medium);
                $em->flush();
            }

            if($form->get('identityCardPicture') && !is_null($form->get('identityCardPicture')->getData()))
            {
                $medium = new Medium();
                $medium->setUploadedFile($form->get('identityCardPicture')->getData());
                $fileManager->upload($medium);
                $recipient->setIdentityCardPicture($medium);

                $em->persist($medium);
                $em->flush();
            }

            $this->addFlash('success', 'Vos documents ont été ajouter avec succé');
        }

        return $this->render('recipient/profil/document.html.twig', [
            "form" => $form->createView(),
            "recipient" => $recipient
        ]);

    }
}