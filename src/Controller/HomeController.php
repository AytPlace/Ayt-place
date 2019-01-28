<?php

namespace App\Controller;

use App\Form\SearchOfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(SearchOfferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('search_offer');

            return $this->redirectToRoute('app_search_result', ['data' => $data]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recherche-resultat/{data]", name="app_search_result")
     */
    public function ResultSearchAction($data = null, OfferRepository $offerRepository)
    {
        $offers = $offerRepository->searchOffer($data['name'], $data['region']);
        dump($offers);die;
    }
}
