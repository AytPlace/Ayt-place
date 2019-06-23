<?php


namespace App\Service;


use App\Entity\Offer;

class PriceManager
{

    public function getPrice(Offer $offer): int
    {
        return $offer->getTravelerNumbers() * $offer->getCostByTraveler();
    }
}
