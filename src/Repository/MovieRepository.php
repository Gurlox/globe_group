<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findForPagination(): ?Query
    {
        return $this->createQueryBuilder('m')
            ->select('m.title')
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();
    }

    public function findDetails(int $id): ?array
    {
        return $this->createQueryBuilder('m')
            ->select(
                'm.title, m.description, m.releaseDate, m.duration, mt.name as movie_type,' .
                'CONCAT(d.firstName, d.lastName) as director, AVG(r.points) as rating'
            )
            ->leftJoin('m.director', 'd')
            ->leftJoin('m.rates', 'r')
            ->leftJoin('m.movieType', 'mt')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
