<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 12/01/19
 * Time: 23:47
 */

namespace App\Controller\recipient;


use App\Entity\Offer;
use App\Form\OfferDateType;
use App\Form\OfferType;
use App\Repository\RecipientRepository;
use App\Service\DateAvailableManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{

    /**
     * @Route("prestataire/offre/", name="recipient_offer_index")
     */
    public function indexAction(Request $request)
    {
        $recipient= $this->getUser();
        $offer = $recipient->getOffers();
        $em = $this->getDoctrine()->getManager();

        if (is_null($offer)) {
            $offer = new Offer();
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        $originalDate = new ArrayCollection();
        foreach ($offer->getAvailabilityOffers() as $availabilityOffer) {
            $originalDate->add($availabilityOffer);
        }

        $offerDateForm = $this->createForm(OfferDateType::class, $offer);
        $offerDateForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offer);
            $em->flush();

            return $this->redirectToRoute('recipient_offer_index');
        }

        if ($offerDateForm->isSubmitted() && $offerDateForm->isValid()) {
            foreach ($originalDate as $date) {
                if (!$offer->getAvailabilityOffers()->contains($date)) {
                    $offer->removeAvailabilityOffer($date);
                    $em->remove($date);
                }
            }

            $em->flush();

            return $this->redirectToRoute('recipient_offer_index');
        }

        return $this->render('recipient/offer/index.html.twig', [
            'recipient' => $recipient,
            'form' => $form->createView(),
            'offerDateForm' => $offerDateForm->createView()
        ]);
    }


    /**
     * @Route("/prestataire/offre/{offer}/delete")
     * @param RecipientRepository $recipientRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Offer $offer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $em->flush();

        return $this->redirectToRoute('recipient_profil_home');
    }

}