<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 23/01/19
 * Time: 00:01
 */

namespace App\Service;


use App\Entity\AvailabilityOffer;
use App\Repository\AvailabilityOfferRepository;

class DateAvailableManager
{
    private $availabilityOfferRepository;

    public function __construct(AvailabilityOfferRepository $availabilityOfferRepository)
    {
        $this->availabilityOfferRepository = $availabilityOfferRepository;
    }

    // Date de début courante = ddc | Date de fin courante = dfc ;; Date dé début = dd | Date de fin = df
    // ( ddc >= dd & ddc <= df ) || (dfc => dd & dfc <= df) || (dd >= ddc & df <= dfc) ===> false
    public function checkDateIntervale(AvailabilityOffer $availabilityOffer) : bool
    {
        $availableDates = $this->availabilityOfferRepository->findAll();
        $available = true;

        foreach ($availableDates as $availableDate) {
            if (
                   ($availabilityOffer->getStartDate()->getTimestamp() >= $availableDate->getStartDate()->getTimestamp() && $availabilityOffer->getStartDate()->getTimestamp() <= $availableDate->getEndDate()->getTimestamp()  )
                || ($availabilityOffer->getEndDate()->getTimestamp() >= $availableDate->getStartDate()->getTimestamp() && $availabilityOffer->getEndDate()->getTimestamp() <= $availableDate->getEndDate()->getTimestamp()   )
                || ($availableDate->getStartDate()->getTimestamp() >= $availabilityOffer->getStartDate()->getTimestamp() && $availableDate->getEndDate()->getTimestamp() <= $availabilityOffer->getEndDate()->getTimestamp() )
            ){
                $available =  false;
            }
        }

        return $available;
    }
}