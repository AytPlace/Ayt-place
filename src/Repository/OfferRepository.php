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

    public function searchOffer(?string $title = null)
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


        return $query->getQuery()->getResult();
    }

    public function getUseCity()
    {
          $offers = $this->findAll();
          $cities = [];

          foreach ($offers as $offer) {
              $key = ucfirst(strtolower($offer->getCity()));
              $cities[$key] = (array_key_exists($key, $cities)) ? $cities[$key] + 1 : 1;
          }

          return $cities;
    }

    public function getLastOffer($limit = 2)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.recipient', 'r')
            ->where('r.status = 1')
            ->orderBy('o.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult()
        ;
    }
}
