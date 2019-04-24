<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 12/01/19
 * Time: 23:47
 */

namespace App\Controller\recipient;


use App\Entity\Medium;
use App\Entity\Offer;
use App\Form\OfferDateType;
use App\Form\OfferImageType;
use App\Form\OfferType;
use App\Repository\RecipientRepository;
use App\Service\DateAvailableManager;
use App\Service\FileManager;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{

    /**
     * @Security("has_role('ROLE_RECIPIENT')")
     * @Route("prestataire/offre", name="recipient_offer_index", methods={"GET", "POST"})
     * @param Request $request
     * @param FileManager $fileManager
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, FileManager $fileManager)
    {
        $recipient= $this->getUser();
        $offer = $recipient->getOffers();
        $em = $this->getDoctrine()->getManager();

        if (is_null($offer)) {
            $offer = new Offer();
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        $offerImageForm = $this->createForm(OfferImageType::class, $offer);
        $offerImageForm->handleRequest($request);

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

        if ($offerImageForm->isSubmitted() && $offerImageForm->isValid()) {
            if($offerImageForm->get('image') && !is_null($offerImageForm->get('image')->getData())) {
                $medium = new Medium();
                $medium->setUploadedFile($offerImageForm->get('image')->getData());
                $fileManager->upload($medium);
                $offer->setImage($medium);

                $em = $this->getDoctrine()->getManager();
                $em->persist($medium);
                $em->flush();

                return $this->redirectToRoute('recipient_offer_index');
            }
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
            'offerDateForm' => $offerDateForm->createView(),
            'offerImageForm' => $offerImageForm->createView()
        ]);
    }


    /**
     * @Security("has_role('ROLE_RECIPIENT')")
     * @Route("/prestataire/offre/{offer}/delete")
     * @param Offer $offer
     * @return RedirectResponse
     */
    public function deleteAction(Offer $offer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $em->flush();

        return $this->redirectToRoute('recipient_profil_home');
    }

}
