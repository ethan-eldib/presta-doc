<?php

namespace App\Repository;

use App\Entity\PackPrestaDoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PackPrestaDoc|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackPrestaDoc|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackPrestaDoc[]    findAll()
 * @method PackPrestaDoc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackPrestaDocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackPrestaDoc::class);
    }

    // /**
    //  * @return PackPrestaDoc[] Returns an array of PackPrestaDoc objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PackPrestaDoc
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
