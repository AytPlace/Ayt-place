<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Recipient;
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
    public function indexAction()
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
     * @param null $data
     * @param OfferRepository $offerRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ResultSearchAction($data = null, OfferRepository $offerRepository)
    {
        $offers = $offerRepository->searchOffer($data['name'], $data['region']);

        return $this->render('home/result.html.twig', [
            'offers' => $offers
        ]);
    }

    /**
     * @Route("/offre/{offer}", name="app_detail_offer")
     * @param Recipient $recipient
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction(Offer $offer)
    {
        return $this->render('home/detail.html.twig', [
            'offer' => $offer
        ]);
    }
}
