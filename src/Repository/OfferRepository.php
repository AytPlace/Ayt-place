<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function searchOffer(?string $title = null, ?string $region = null)
    {
        $query = $this->createQueryBuilder('o')
            ->leftJoin('o.recipient', 'r')->addSelect('r')
            ->where('r.status = 1')
            ->orderBy('o.updatedAt', 'DESC')
        ;

        if (!empty($title)) {
            $query->andWhere('o.title LIKE :title')
                ->setParameter('title', '%'.$title.'%')
            ;
        }

        if (!empty($region)) {
            $query->andWhere('o.region = :region')
                ->setParameter('region', $region)
            ;
        }

        return $query->getQuery()->getResult();
    }
}
