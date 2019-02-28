<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Recipient;
use App\Entity\Status;
use App\Entity\User;
use App\Form\ClientForgotPasswordType;
use App\Form\RecipientFormType;
use App\Form\RecipientType;
use App\Form\UpdatePasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\EmailManager;
use App\Service\RandomStringGenerator;
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
     * @Route("/connexion", name="app_login")
     *
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
     * @Route("/inscription", name="app_client_registration")
     */
    public function registrationAction(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, RegisterManager $registerManager)
    {
        $user = new Client();
        $form = $this->createForm(UserType::class, $user, ['use_password' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($registerManager->getDuplicateEmail($user->getEmail())) {

                return $this->render('security/registration.html.twig', [
                    "form" => $form->createView()
                ]);
            }
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/prestataire/inscription", name="app_registration")
     */
    public function recipientRegistrationAction(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, RegisterManager $registerManager)
    {
        $recipient = new Recipient();
        $form = $this->createForm(RecipientType::class, $recipient, ['use_password' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($recipient);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/activation/{token}", name="app_index_enable")
     * @param string $token
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function enableEmailAction(string $token, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['enableToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Lien invalide merci de contacter notre service client');

            return $this->render('security/enable.html.twig');
        }

        $user->setEnable(true);
        $user->setEnableToken(null);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_login', ['message' => 'votre compte à bien étais activé']);
    }

    /**
     * @Route("/mot-de-passe-oublie", name="app_client_forgot_password")
     */
    public function forgotClientPassword(Request $request, RandomStringGenerator $randomStringGenerator, EmailManager $emailManager)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ClientForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $data["email"]]);

            if (!$user) {
                $user->setToken($randomStringGenerator->generate(15));
                $user->setTokenRequestAt(new \DateTime());

                $em->flush();

                $emailManager->sendCollectivePasswordEmail($user);
                $this->addFlash('success',"Vous allez recevoir un email à l'adresse ".$data["email"]." contenant un lien pour réinitialiser votre mot de passe.");

                return $this->redirectToRoute('app_login');
            }else{
                $this->addFlash('error',"Vous allez recevoir un email à l'adresse ".$data["email"]." contenant un lien pour réinitialiser votre mot de passe.");
            }
        }else{
            $this->addFlash('error', 'Veuillez entrer votre email ');
        }

        return $this->render('security/client-forgot-password.twig', [
            'form' => $form->createView(    )
        ]);
    }

    /**
     * @Route("/changement-mot-de-passe/{token}", name="app_client_update_password")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['token' => $token]);

        $date = new \DateTime();
        $date = $date->sub(new \DateInterval('P2D'));

        if (!is_null($user)  && $user->getTokenRequestAt() > $date) {
            $form = $this->createForm(UpdatePasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($userPasswordEncoder->encodePassword($user,$form->get('password')->getData()));
                $user->setToken(null);
                $user->setTokenRequestAt(null);
                $em->flush();

                $this->addFlash('success', 'Votre mot de passe a été mis à jour.');

                return $this->redirectToRoute('app_login');
            }

        }else {
            $this->addFlash('error', 'Votre token de réinitialisation de mot de passe est invalide.');
        }

        return $this->render('security/client-update-password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
