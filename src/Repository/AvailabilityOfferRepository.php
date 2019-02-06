<?php

namespace App\Repository;

use App\Entity\AvailabilityOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AvailabilityOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvailabilityOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvailabilityOffer[]    findAll()
 * @method AvailabilityOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailabilityOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AvailabilityOffer::class);
    }

    public function getAvailableOffer()
    {
        return $this->createQueryBuilder('o')
            ->where('o.available = true')
            ->orderBy('o.startDate', 'DESC')
            ->getQuery()->getResult()
        ;
    }

    public function checkBookingOffer($startDate, $endDate)
    {
        return $this->createQueryBuilder('o')
            ->where('o.available = true')
            ->andWhere('o.startDate <= :startDate')
            ->andWhere('o.endDate >= :endDate')
            ->setMaxResults(1)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()->getResult()
        ;
    }
}
