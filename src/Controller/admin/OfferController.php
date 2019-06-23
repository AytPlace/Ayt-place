<?php


namespace App\Controller\admin;


use App\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OfferController
 * @package App\Controller\admin
 * @Route("/admin")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/offres", name="app_admin_offer_index")
     */
    public function indexAction()
    {
        $offerRepository = $this->getDoctrine()->getRepository(Offer::class);
        return $this->render('admin/offer/index.html.twig', [
            'offers' => $offerRepository->findAll()
        ]);
    }

    /**
     * @param Offer $offer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/offre/{id}", name="app_admin_offer_view")
     */
    public function viewAction(Offer $offer)
    {
        return $this->render('admin/offer/detail.html.twig', [
            'offer' => $offer
        ]);
    }
    /**
     * @param Offer $offer
     * @Route("/delete/{id}", name="app_admin_offer_remove")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Offer $offer)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($offer);
        $em->flush();

        return $this->redirectToRoute('app_admin_offer_index');
    }
}
