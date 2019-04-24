<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 15:59
 */

namespace App\Controller\recipient;


use App\Entity\Response;
use App\Entity\User;
use App\Form\ChattingType;
use App\Repository\RequestRepository;
use App\Entity\Request as BookingRequest;
use App\Service\EmailManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    private $status = User::STATUS;

    /**
     * @Security("has_role('ROLE_RECIPIENT')")
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
     * @Security("has_role('ROLE_RECIPIENT')")
     * @Route("/prestataire/demande/{bookingRequest}", name="recipient_request_view", methods={"GET", "POST"})
     * @param BookingRequest $bookingRequest
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function detailAction(BookingRequest $bookingRequest, Request $request)
    {
        $response = new Response();
        $form = $this->createForm(ChattingType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bookingRequest->addResponse($response);

            $em->persist($response);
            $em->flush();

            return $this->redirectToRoute('recipient_request_view', ['bookingRequest' => $bookingRequest->getId()]);
        }

        return $this->render('recipient/offer/chatting.html.twig', [
            'client' => $bookingRequest->getClients()[0],
            'form' => $form->createView(),
            'responses' => $bookingRequest->getResponses()
        ]);
    }

    /**
     * @Route("/prestataire/demande/{request}/refuse", name="recipient_request_refuse")
     * @param BookingRequest $request
     * @param EmailManager $emailManager
     * @return RedirectResponse
     */
    public function refuseAction(BookingRequest $request, EmailManager $emailManager)
    {

        $emailManager->sendDisableRequestToClient($request->getClients()[0], $request);
        $em = $this->getDoctrine()->getManager();

        $request->setStatus($this->status["Refuser"]);
        $em->flush();

        return $this->redirectToRoute('recipient_request_index');
    }

    /**
     * @Route("/prestataire/demande/{request}/accepte", name="recipient_request_accept")
     * @param BookingRequest $request
     * @param EmailManager $emailManager
     * @return RedirectResponse
     */
    public function acceptAction(BookingRequest $request, EmailManager $emailManager)
    {
        $request->setStatus($this->status["Valider"]);

        $emailManager->sendEnableRequestToCLient($request->getClients()[0], $request);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('recipient_request_index');
    }
}
