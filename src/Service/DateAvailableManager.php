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
    public function checkDateAvailable(AvailabilityOffer $availabilityOffer) : bool
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

    public function checkDateInterval(AvailabilityOffer $availabilityOffer)
    {
        return ($availabilityOffer->getStartDate() > $availabilityOffer->getEndDate()) ? false : true;
    }

    private function parseDateInterval($form)
    {
        return explode(' - ',  $form->getData()["intervalDate"]);
    }

    public function checkBooking($form)
    {
        $dates = $this->parseDateInterval($form);

        $startDate = new \Datetime(date('Y-m-d H:i:s',strtotime($dates[0])));
        $endDate = new \Datetime(date('Y-m-d H:i:s',strtotime($dates[1])));

        $availableOffer = $this->availabilityOfferRepository->checkBookingOffer($startDate, $endDate);

        return (!is_null($availableOffer)) ? $availableOffer[0] : null;
    }
}