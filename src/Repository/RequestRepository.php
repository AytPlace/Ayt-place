<?php

namespace App\Repository;

use App\Entity\AvailabilityOffer;
use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Request::class);
    }


    public function getRequests(AvailabilityOffer $availableOffer)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.availabilityOffer = :availableOffer')
            ->setParameter('availableOffer', $availableOffer)
            ->getQuery()->getResult()
        ;
    }

    public function userHasRequest($clientId)
    {
        $statement = $this->getEntityManager()->getConnection()->prepare("SELECT request_id FROM users_requests WHERE users_requests.client_id = :clientId");
        $statement->bindValue('clientId', $clientId);
        $statement->execute();

        return $statement->fetchAll();
    }
}
