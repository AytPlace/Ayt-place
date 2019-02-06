<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Request as BookingRequest;
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
        $bookingRequest = new BookingRequest();
        $form = $this->createForm(SelectDateType::class, $bookingRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($request->request);die;
            $dateInterval = $dateAvailableManager->checkBooking($form);

            if (is_null($dateInterval)) {
                $this->addFlash('error', "Cette date n'est pas valide");

                return $this->render('home/detail.html.twig', [
                    'offer' => $offer,
                    'form' => $form->createView()
                ]);
            }

            $dateInterval->setAvailable(false);
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
