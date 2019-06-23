<?php


namespace App\Controller\admin;


use App\Entity\Client;
use App\Entity\Recipient;
use \Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RecipientType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\admin
 * @Route("/admin")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="app_admin_user_index")
     */
    public function index()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @Route("/modifier/{id}", name="app_admin_user_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user, Request $request)
    {
        if ($user instanceof Client) {
            $userForm = $this->createForm(UserType::class, $user);
        }

        if ($user instanceof Recipient) {
            $userForm = $this->createForm(RecipientType::class, $user);
        }

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $userForm->createView()
        ]);

    }

    /**
     * @param User $user
     * @Route("/supprimer/{id}", name="app_admin_remove_user")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_index');
    }
}
