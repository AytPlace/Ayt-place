<?php

namespace App\Controller;

use App\Entity\Medium;
use App\Form\ProfilePictureType;
use App\Form\UserType;
use App\Service\FileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @Route("/client/profile", name="app_index_profil_index", methods={"GET"})
     */
    public function indexAction()
    {
        return $this->render('profile/index.html.twig', [
            'client' => $this->getUser()
        ]);
    }


    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @Route("/client/profile/modifier", name="app_index_profil_edit", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, FileManager $fileManager)
    {
        $user = $this->getUser();
        $profilForm = $this->createForm(UserType::class, $user);
        $pictureForm = $this->createForm(ProfilePictureType::class, $this->getUser());

        $profilForm->handleRequest($request);
        $pictureForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_index_profil_index');
        }

        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {

            if($pictureForm->get('profilePicture') && !is_null($pictureForm->get('profilePicture')->getData())) {
                $medium = new Medium();
                $medium->setUploadedFile($pictureForm->get('profilePicture')->getData());
                $fileManager->upload($medium);
                $user->setProfilePicture($medium);

                $em = $this->getDoctrine()->getManager();
                $em->persist($medium);
                $em->flush();

                return $this->redirectToRoute('app_index_profil_index');
            }
        }

        return $this->render('profile/edit.html.twig', [
            'profilForm' => $profilForm->createView(),
            'pictureForm' => $pictureForm->createView(),
        ]);
    }
}
