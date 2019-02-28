<?php

namespace App\Controller;

use App\Entity\Request as BookingRequest;
use App\Entity\User;
use App\Repository\RequestRepository;
use App\Service\EmailManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/client/booking/offer/{request}", name="app_index_booking_delete")
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
