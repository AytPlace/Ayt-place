<?php

namespace App\Controller;

use App\Entity\Offer;

use App\Entity\Recipient;
use App\Entity\Request as BookingRequest;
use App\Form\SearchOfferType;
use App\Form\SelectDateType;
use App\Repository\AvailabilityOfferRepository;
use App\Repository\OfferRepository;
use App\Service\DateAvailableManager;
use App\Service\EmailManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function indexAction(OfferRepository $offerRepository)
    {
        $offers = $offerRepository->getLastOffer();

        return $this->render('home/index.html.twig', [
            'offers' => $offers
        ]);
    }

    /**
     * @param Request $request
     * @param OfferRepository $offerRepository
     * @return Response
     * @Route("/recherche", name="app_index_search", methods={"GET", "POST"})
     */
    public function searchAction(Request $request, OfferRepository $offerRepository)
    {
        $data = $request->request->get('search_offer');
        $offers = $offerRepository->searchOffer($data['name']);

        $useRegion = $offerRepository->getUseCity();
        $prices = $offerRepository->getPrices();
        $form = $this->createForm(SearchOfferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('search_offer');
            $offers = $offerRepository->searchOffer($data['name']);

            return $this->render('home/search.html.twig', [
                'form' => $form->createView(),
                'offers' => $offers,
                'useCities' => $useRegion
            ]);
        }

        return $this->render('home/search.html.twig', [
            'form' => $form->createView(),
            'offers' => $offers,
            'useCities' => $useRegion
        ]);
    }

    /**
     * @Route("/offre/{offer}", name="app_index_detail_offer", methods={"GET", "POST"})
     * @param Offer $offer
     * @param Request $request
     * @param DateAvailableManager $dateAvailableManager
     * @param EmailManager $emailManager
     * @param OfferRepository $offerRepository
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function detailAction(Offer $offer, Request $request, DateAvailableManager $dateAvailableManager, EmailManager $emailManager, OfferRepository $offerRepository)
    {
        $offers = $offerRepository->getLastOffer(3, $offer);
        $bookingRequest = new BookingRequest();

        $form = $this->createForm(SelectDateType::class, $bookingRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateInput = $request->request->get('select_date');
            $dateInterval = $dateAvailableManager->checkBooking($dateInput, $offer);

            if (is_null($dateInterval)) {
                $this->addFlash('error', "Cette date n'est pas valide");

                return $this->render('home/detail.html.twig', [
                    'offer' => $offer,
                    'offers' => $offers,
                    'form' => $form->createView()
                ]);
            }

            $this->getUser()->addRequest($bookingRequest);
            $bookingRequest->addOffer($offer);
            $dates = $dateAvailableManager->parseDateInterval($dateInput);
            $dateAvailableManager->setBooking($bookingRequest, $dateInterval, $dates);

            $emailManager->sendSendBookingOffer($this->getUser(), $offer->getTitle());


            return $this->redirectToRoute('app_index_booking_offer');
        }

        return $this->render('home/detail.html.twig', [
            'offer' => $offer,
            'offers' => $offers,
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Request $request
     * @param OfferRepository $offerRepository
     * @return Response
     * @Route("/offer-filter", name="app_index_filter_offer", methods={"GET", "POST"})
     */
    public function getFilterOffer(Request $request, OfferRepository $offerRepository)
    {
        $regions = $request->request->get('regions');
        $capacity = $request->request->get('capacity');

        $offers = $offerRepository->searchOffer(null, $regions, $capacity);

        return $this->render('includes/searchContent.html.twig', [
            'offers' => $offers
        ]);    $dateAvailableManager->getUnbookDate($offer);
    }

    /**
     * @Route("/recherche-resultat/{offer}", name="app_search_result")
     * @param Offer $offer
     * @param DateAvailableManager $dateAvailableManager
     * @return Response
     * @throws \Exception
     */
    public function getAvailableOfferDates(Offer $offer, DateAvailableManager $dateAvailableManager)
    {

        return new JsonResponse([
            'dates' => $dateAvailableManager->getUnbookDate($offer)
        ]);
    }
}
