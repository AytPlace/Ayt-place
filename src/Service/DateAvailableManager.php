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
use Doctrine\ORM\EntityManagerInterface;

class DateAvailableManager
{
    private $availabilityOfferRepository;
    private $requestRepository;
    private $em;

    public function __construct(AvailabilityOfferRepository $availabilityOfferRepository, RequestRepository $requestRepository, EntityManagerInterface $entityManager)
    {
        $this->availabilityOfferRepository = $availabilityOfferRepository;
        $this->requestRepository = $requestRepository;
        $this->em = $entityManager;
    }

    /**
     *
     *  Date de début courante = ddc | Date de fin courante = dfc ;; Date dé début = dd | Date de fin = df
     *  ( ddc >= dd & ddc <= df ) || (dfc => dd & dfc <= df) || (dd >= ddc & df <= dfc) ===> false
     * @param AvailabilityOffer $availabilityOffer
     * @param Offer $offer
     * @return bool
     */
    public function checkDateAvailable(AvailabilityOffer $availabilityOffer, Offer $offer) : bool
    {
        $availableDates = $this->availabilityOfferRepository->findBy(['offer' => $offer]);
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
     * @return array
     * @throws \Exception
     */
    public function getUnbookDate(Offer $offer): array
    {
        $availableDates = $this->availabilityOfferRepository->findBy(['offer' => $offer]);

        $data = [];
        $disableDate = [];

        foreach ($availableDates as $availableDate) {
            $booksDate = $this->requestRepository->getRequests($availableDate);
            $globalDiff = $availableDate->getStartDate()->diff($availableDate->getEndDate());

            if (count($booksDate) > 0) {
                $disableDate = [];
                // generate all disable date
                foreach ($booksDate as $bookDate) {
                    $startDiff = $bookDate->getStartDate()->diff($availableDate->getStartDate());
                    $requestDiff = $bookDate->getEndDate()->diff($bookDate->getStartDate());

                    for ($i = 0; $i <= $requestDiff->d; $i++) {
                        $currentTimestamp  = $bookDate->getStartDate()->getTimestamp();
                        $currentDate = new \DateTime();
                        $currentDate->setTimestamp($currentTimestamp);

                        $disableDate[] = $currentDate->add(new DateInterval('P'.($i).'D'))->format('Y-m-d');
                    }
                }

                // if not disable add  to available date
                for ($i = 0; $i <= $globalDiff->d; $i++) {
                    $currentDate = new \DateTime();
                    $currentDate->setTimestamp($availableDate->getStartDate()->getTimestamp());
                    $currentDate = $currentDate->add(new DateInterval('P'.($i).'D'))->format('Y-m-d');

                    if (!in_array($currentDate, $disableDate)) {
                        $data[] = $currentDate;
                    }
                }
            }else {
                for ($i = 0; $i <= $globalDiff->d; $i++) {
                    $currentDate = new \DateTime();
                    $currentDate->setTimestamp( $availableDate->getStartDate()->getTimestamp());
                    $data[] = $currentDate->add(new DateInterval('P'.($i).'D'))->format('Y-m-d');
                }
            }

        }

        return $data;
    }

    public function setBooking(Request $bookingRequest, AvailabilityOffer $dateInterval, $dates)
    {
        $bookingRequest->setAvailableOffer($dateInterval);
        $dateInterval->addRequest($bookingRequest);
        $bookingRequest->setStartDate($dates["startDate"]);
        $bookingRequest->setEndDate($dates["endDate"]);

        $this->em->persist($bookingRequest);
        $this->em->flush();
    }

    public function displayBookingDate(Request $request)
    {
        return $request->getEndDate()->diff($request->getStartDate())->d;
    }
}