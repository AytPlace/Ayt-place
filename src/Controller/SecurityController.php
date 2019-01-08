<?php

namespace App\Controller;

use App\Entity\Recipient;
use App\Entity\Status;
use App\Entity\User;
use App\Form\ClientForgotPasswordType;
use App\Form\RecipientFormType;
use App\Form\RecipientType;
use App\Form\UpdatePasswordType;
use App\Form\UserType;
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
     * @Route("/client/connexion", name="app_login")
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
     * @Route("/prestataire/connexion", name="recipient_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function recipientLogin(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/recipient-login.html.tiwg', ['last_username' => $lastUsername, 'error' => $error]);
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

            $user->setRoles(['ROLE_CLIENT']);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

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
        $form = $this->createForm(RecipientType::class, $recipient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($registerManager->getDuplicateEmail($recipient->getEmail())) {
                $form->addError(new FormError("Cette email est dèjà utiliser"));

                return $this->render('security/registration.html.twig', [
                    "form" => $form->createView()
                ]);
            }

            $status = $em->getRepository(Status::class)->findOneBy(['name' => 'a valider']);

            $recipient->setStatus($status);
            $recipient->setRoles(['ROLE_RECIPIENT']);
            $recipient->setPassword($userPasswordEncoder->encodePassword($recipient, $recipient->getPassword()));

            $em->persist($recipient);
            $em->flush();

            return $this->redirectToRoute('recipient_login');
        }

        return $this->render('security/registration.html.twig', [
            "form" => $form->createView()
        ]);
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

            if (!is_null($user) && !$user instanceof Recipient) {
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
            $this->addFlash('error', 'Une erreur est survenue.');
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

        if (!is_null($user) && !$user instanceof Recipient  && $user->getTokenRequestAt() > $date) {
            $form = $this->createForm(UpdatePasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($userPasswordEncoder->encodePassword($user,$form->get('password')->getData()));
                $user->setToken(null);
                $user->setTokenRequestAt(null);
                $em->flush();

                $this->addFlash('sucess', 'Votre mot de passe a été mis à jour.');

                return $this->redirectToRoute('app_login');
            }

        }else {
            $this->addFlash('error', 'Votre token de réinitialisation de mot de passe est invalide.');
        }

        return $this->render('security/client-update-password.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/client/deconnexion", name="client_logout")
     */
    public function clientLogout()
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/prestataire/deconnexion", name="recipient_logout")
     */
    public function recipientLogout()
    {
        return $this->redirectToRoute('recipient_login');
    }
}
