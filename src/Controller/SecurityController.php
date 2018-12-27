<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\RegisterManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/inscription", name="app_registration")
     */
    public function registrationAction(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, RegisterManager $registerManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($registerManager->getDuplicateEmail($user->getEmail())) {
                $form->addError(new FormError("Cette email est dèjà utiliser"));

                return $this->render('security/registration.html.twig', [
                    "form" => $form->createView()
                ]);
            }

            $user->setRoles(['ROLE_USER']);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
