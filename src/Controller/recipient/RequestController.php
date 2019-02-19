<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 15:59
 */

namespace App\Controller\recipient;


use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{

    /**
     * @Route("/prestataire/demande", name="recipient_request_index")
     * @param RequestRepository $requestRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(RequestRepository $requestRepository)
    {
        dump($this->getUser()->getOffers()->getRequest());die;
        $requestsId = $requestRepository->recipientHasRequest($this->getUser()->getOffers()->getId());

        $requests = [];
        foreach ($requestsId as $requestId) {
            $requests[] = $requestRepository->find($requestId["request_id"]);
        }

        return $this->render('recipient/request/index.html.twig', [
            'requests' => $requests
        ]);
    }
}