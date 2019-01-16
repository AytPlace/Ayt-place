<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 10/01/19
 * Time: 20:39
 */

namespace App\Controller\recipient;

use App\Entity\Medium;
use App\Form\ProfilePictureType;
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
    public function indexAction(Request $request, FileManager $fileManager)
    {
        $recipient = $this->getUser();
        $form = $this->createForm(UpdateRecipientType::class, $this->getUser());
        $pictureForm = $this->createForm(ProfilePictureType::class, $this->getUser());
        $sirenForm = $this->createForm(SirenType::class, $recipient);

        $form->handleRequest($request);
        $pictureForm->handleRequest($request);
        $sirenForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('recipient_profil_home');
        }

        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {

            if($pictureForm->get('profilePicture') && !is_null($pictureForm->get('profilePicture')->getData())) {
                $medium = new Medium();
                $medium->setUploadedFile($pictureForm->get('profilePicture')->getData());
                $fileManager->upload($medium);
                $recipient->setProfilePicture($medium);

                $em = $this->getDoctrine()->getManager();
                $em->persist($medium);
                $em->flush();

                return $this->redirectToRoute('recipient_profil_home');
            }
        }

        if ($sirenForm->isSubmitted() && $sirenForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($sirenForm->get('sirenPicture') && !is_null($sirenForm->get('sirenPicture')->getData()))
            {
                $medium = new Medium();
                $medium->setUploadedFile($sirenForm->get('sirenPicture')->getData());
                $fileManager->upload($medium);
                $recipient->setSirenPicture($medium);

                $em->persist($medium);
                $em->flush();
            }

            if($sirenForm->get('identityCardPicture') && !is_null($sirenForm->get('identityCardPicture')->getData()))
            {
                $medium = new Medium();
                $medium->setUploadedFile($sirenForm->get('identityCardPicture')->getData());
                $fileManager->upload($medium);
                $recipient->setIdentityCardPicture($medium);

                $em->persist($medium);
                $em->flush();
            }

            $this->addFlash('success', 'Vos documents ont été ajouter avec succé');
        }

        return $this->render('recipient/profil/index.html.twig', [
            'form' => $form->createView(),
            'formPicture' => $pictureForm->createView(),
            'formSiren' => $sirenForm->createView(),
            'recipient' => $this->getUser()
        ]);
    }
}