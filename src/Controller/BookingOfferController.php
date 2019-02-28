<?php

namespace App\Controller;

use App\Entity\Request as BookingRequest;
use App\Entity\Response;
use App\Form\ChattingType;
use App\Service\EmailManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingOfferController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_CLIENT')")
     * @Route("/client/booking/offer", name="app_index_booking_offer")
     */
    public function index()
    {
        return $this->render('booking_offer/index.html.twig', [
            'requests' => $this->getUser()->getRequests(),
        ]);
    }

    /**
     * @Route("/client/discussion/{bookingRequest}", name="app_index_chatting")
     */
    public function chatting(BookingRequest $bookingRequest, Request $request)
    {
        $response = new Response();
        $form = $this->createForm(ChattingType::class, $response);
        $responses = $bookingRequest->getResponses();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bookingRequest->addResponse($response);
            $response->SetSender("client");

            $em->persist($response);
            $em->flush();

            return $this->redirectToRoute('app_index_chatting', ['bookingRequest' => $bookingRequest->getId()]);
        }

        return $this->render('booking_offer/chatting.html.twig', [
            'recipient' => $bookingRequest->getOffers()[0]->getRecipient(),
            'form' => $form->createView(),
            'responses' => $responses
        ]);
    }

    /**
     * @Route("/client/booking/offer/{request}/delete", name="app_index_booking_delete")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeBooking(BookingRequest $request, EmailManager $emailManager)
    {
        $emailManager->sendRemoveRecipientOffer($request);
        $em = $this->getDoctrine()->getManager();
        $em->remove($request);
        $em->flush();

        return $this->redirectToRoute('app_index_booking_offer');
    }
}
