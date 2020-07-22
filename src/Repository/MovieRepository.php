<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }
    
    /**
    * @return Movie Returns a single Movie object
    */
    public function findComplete ($id)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->leftJoin('m.genres', 'g');
        $qb->leftJoin('m.castings', 'c');
        $qb->leftJoin('c.person', 'p');
        $qb->addSelect('g, c, p'); 
        $qb->where('m.id = :value');
        $qb->setParameter('value', $id);

        return $qb->getQuery()->getSingleResult(); 
    }

     /**
    * @param int 
    */
    public function findOrderByTitle ($limit = 20)
    {
        return $this->createQueryBuilder('m')
                    ->orderBy('m.title', 'ASC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
