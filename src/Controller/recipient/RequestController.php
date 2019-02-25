<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 15:59
 */

namespace App\Controller\recipient;


use App\Repository\RequestRepository;
use App\Entity\Request as BookingRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{

    /**
     * @Route("/prestataire/demande", name="recipient_request_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('recipient/request/index.html.twig', [
            'requests' => $this->getUser()->getOffers()->getRequests()
        ]);
    }

    /**
     * @Route("/prestataire/demande/{request}", name="recipient_request_view")
     * @TODO Najla faire ici le systme de discusion entre client et prestataire
     * @param BookingRequest $bookingRequest
     */
    public function detailAction(BookingRequest $request)
    {
        dump($request);die;
    }

    /**
     * @Route("/prestataire/demande/{request}/refuse", name="recipient_request_refuse")
     */
    public function refuseAction(BookingRequest $request)
    {
        dump($request);die;
    }

    /**
     * @Route("/prestataire/demande/{request}/accepte", name="recipient_request_accept")
     */
    public function acceptAction(BookingRequest $request)
    {
        dump($request);die;
    }
}