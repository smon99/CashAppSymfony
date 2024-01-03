<?php

namespace App\Repository;

use App\Entity\TransactionReceiverValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransactionReceiverValue>
 *
 * @method TransactionReceiverValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionReceiverValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionReceiverValue[]    findAll()
 * @method TransactionReceiverValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionReceiverValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionReceiverValue::class);
    }

//    /**
//     * @return TransactionReceiverValue[] Returns an array of TransactionReceiverValue objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TransactionReceiverValue
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
