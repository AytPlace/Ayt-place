<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\SearchOfferType;
use App\Form\SelectDateType;
use App\Repository\OfferRepository;
use App\Service\DateAvailableManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function indexAction(Request $request, OfferRepository $offerRepository)
    {

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
        ]);
    }

    /**
     * @Route("/offre/{offer}", name="app_index_detail_offer")
     */
    public function detailAction(Offer $offer, Request $request, DateAvailableManager $dateAvailableManager)
    {
        $form = $this->createForm(SelectDateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dates = explode(' - ',  $form->getData()["intervalDate"]);
            $dateAvailableManager->checkBooking($dates[0], $dates[1]);
        }

        return $this->render('home/detail.html.twig', [
            'offer' => $offer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Offer $offer
     * @Route("/date-offre/{offer}", name="app_index_get_available_date")
     * @return JsonResponse
     */
    public function getAvailableOfferDates(Offer $offer)
    {
        $data = [];
        foreach ($offer->getAvailabilityOffers() as $availabilityOffer) {
            $data[] = ["startDate" => $availabilityOffer->getStartDate(), "endDate" => $availabilityOffer->getEndDate()];
        }

        return new JsonResponse([
            'dates' => $data
        ]);
    }
}
