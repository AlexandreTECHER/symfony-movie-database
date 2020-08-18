<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
     * @return Movie Return a Movie objects
     */
    public function findComplete($id)
    {
        // Un peu ocmme ndans le contrôleur, on utilise la méthode createQueryBuilder()
        // Cependant, ce n'est pas exactement la mème
        // Ici elle n'est pas exécutée depusi l'EntityManager mais depuis le Repository
        // Donc elle prend, par défaut, en compte qu'on veut des Movie
        // ça veut dire que le SELECT et le FROM sont déja déterminés
        // C'est pour ça qu'on doit tout de suite préciser le alias de l'entité
        // en argument de createQueryBuilder(). Ici c'est 'm'
        $qb = $this->createQueryBuilder('m');
        $qb->leftJoin('m.genres', 'g');
        // $qb->leftJoin('m.castings', 'c');
        // $qb->leftJoin('c.person', 'p');
        $qb->leftJoin('m.employees', 'e');
        $qb->leftJoin('e.person', 'p');
        // On utilise addSelect() pour préciser qu'on veut aussi sélectionner les Genre, les Casting et les Person
        // Ces trois entités s'ajoutent à Movie qui était dans le select grace à createQueryBuilder()
        $qb->addSelect('g, e, p');

        // Comme pour le PHP natif, on bind la valeur avec setParameter()
        // Les paramètres de la requêtes s'écrivent toujours avec un deux-points devant
        $qb->where('m.id = :id');
        $qb->andWhere('e INSTANCE OF App\Entity\Actor');
        $qb->setParameter('id', $id); // Remarquez qu'on ne reprecise pas les deux-points ici dans le nom du paramètre

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param int $limit = 20 The Number of results
     */
    public function findOrderByTitle($limit = 20)
    {
        // Dans le repository, le but est de fournir soit
        // - un objet QueryBuilder
        // - un ensemble de résultat
        // Pour obtenir ces résultats, on peut utiliser le QueryBuilder comme on vient de le faire
        // return $this->createQueryBuilder('m')
        // ->orderBy('m.title', 'ASC')
        // ->setMaxResults($limit)
        // ->getQuery()
        // ->getResult()
        // ;

        // On peut aussi directement utiliser du DQL
        return $this->getEntityManager()
                ->createQuery('SELECT m FROM App\Entity\Movie m ORDER BY m.title ASC')
                ->setMaxResults($limit)
                ->getResult()
        ;
    }
}
