<?php

namespace App\Repository;

use App\Entity\SensorMeasure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SensorMeasure|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensorMeasure|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensorMeasure[]    findAll()
 * @method SensorMeasure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorMeasureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SensorMeasure::class);
    }

    // /**
    //  * @return SensorMeasure[] Returns an array of SensorMeasure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SensorMeasure
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
