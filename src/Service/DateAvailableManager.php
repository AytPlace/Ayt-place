<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 23/01/19
 * Time: 00:01
 */

namespace App\Service;


use App\Entity\AvailabilityOffer;
use App\Entity\Offer;
use App\Entity\Request;
use App\Repository\AvailabilityOfferRepository;
use App\Repository\RequestRepository;
use DateInterval;

class DateAvailableManager
{
    private $availabilityOfferRepository;
    private $requestRepository;

    public function __construct(AvailabilityOfferRepository $availabilityOfferRepository, RequestRepository $requestRepository)
    {
        $this->availabilityOfferRepository = $availabilityOfferRepository;
        $this->requestRepository = $requestRepository;
    }

    /**
     *
     *  Date de début courante = ddc | Date de fin courante = dfc ;; Date dé début = dd | Date de fin = df
     *  ( ddc >= dd & ddc <= df ) || (dfc => dd & dfc <= df) || (dd >= ddc & df <= dfc) ===> false
     * @param AvailabilityOffer $availabilityOffer
     * @return bool
     */
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

    /**
     * Vérifie que la date de début < date de fin
     * @param AvailabilityOffer $availabilityOffer
     * @return bool
     */
    public function checkDateInterval(AvailabilityOffer $availabilityOffer): bool
    {
        return ($availabilityOffer->getStartDate() > $availabilityOffer->getEndDate()) ? false : true;
    }

    /**
     * Parse le champ dateIntervapl en 2 datetime
     * @param $bookingRequest
     * @return array
     * @throws \Exception
     */
    public function parseDateInterval($bookingRequest) : array
    {
        $dates = explode(' - ',  $bookingRequest["intervalDate"]);

        return ["startDate" => new \DateTime(date('Y-m-d H:i:s',strtotime($dates[0]))), "endDate" => new \DateTime(date('Y-m-d H:i:s',strtotime($dates[1])))];
    }

    /**
     * Check si l'interval soumit et compris dans l'interval de réservation
     * @param $form
     * @return AvailabilityOffer|null
     * @throws \Exception
     */
    public function checkBooking($form): ?AvailabilityOffer
    {
        $dates = $this->parseDateInterval($form);

        $startDate = $dates["startDate"];
        $endDate = $dates["endDate"];

        $availableOffer = $this->availabilityOfferRepository->checkBookingOffer($startDate, $endDate);

        return (count($availableOffer) > 0) ? $availableOffer[0] : null;
    }

    /**
     * Récupére les dates qui son encore disponible pour une offre
     * @param Offer $offer
     */
    public function getUnbookDate(Offer $offer)
    {
        $availableDates = $this->availabilityOfferRepository->findBy(['offer' => $offer]);
        $data = [];
        foreach ($availableDates as $availableDate) {
            $booksDate = $this->requestRepository->getRequests($availableDate);
            $globalDiff = $availableDate->getStartDate()->diff($availableDate->getEndDate());
            foreach ($booksDate as $bookDate) {
                $startDiff = $bookDate->getStartDate()->diff($availableDate->getStartDate());
                $requestDiff = $bookDate->getEndDate()->diff($bookDate->getStartDate());

                for ($i = 0; $i <= $globalDiff->d; $i++) {
                    $currentDate = new \DateTime();
                    $currentDate->setTimestamp( $availableDate->getStartDate()->getTimestamp());

                    if ($i  < $startDiff->d || $i > $requestDiff->d +1 ) {
                        $data[] = $currentDate->add(new DateInterval('P'.($i).'D'))->format('Y-m-d');
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Fermer un Interval qui n'as plus de jour libre
     */
    public function closeAvailableOffer()
    {

    }
}