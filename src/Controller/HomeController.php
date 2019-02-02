<?php

namespace App\Controller;

use App\Entity\Offer;
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
    public function indexAction(Request $request, OfferRepository $offerRepository)
    {
        $offers = [];

        $form = $this->createForm(SearchOfferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('search_offer');
            $offers = $offerRepository->searchOffer($data['name'], $data['region']);

            return $this->render('home/index.html.twig', [
                'form' => $form->createView(),
                'offers' => $offers
            ]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'offers' => $offers
        ]);
    }

    /**
     * @Route("/offre/{offer}", name="app_index_detail_offer")
     */
    public function detailAction(Offer $offer)
    {
        dump($offer);die;
    }
}
