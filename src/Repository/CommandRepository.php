<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Command>
 *
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Command $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Command $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAllCommandByStatus($dateMin, $dateMax, $status1, $status2 = 0, $status3 = 0, $status4 = 0)
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status_1 OR c.status = :status_2 OR c.status = :status_3 OR c.status = :status_4')
            ->andWhere('c.createdAt > :date_min')
            ->andWhere('c.createdAt < :date_max')
            ->setParameter('status_1', $status1)
            ->setParameter('status_2', $status2)
            ->setParameter('status_3', $status3)
            ->setParameter('status_4', $status4)
            ->setParameter('date_min', $dateMin)
            ->setParameter('date_max', $dateMax)
            ->getQuery()->getResult();
    }

    ########### Left Join Exemple ######################
    public function getAllCommandsByStatusWitNewUsers($dateMin, $dateMax, $status1, $status2 = 0, $status3 = 0, $status4 = 0)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->where('c.status = :status_1 OR c.status = :status_2 OR c.status = :status_3 OR c.status = :status_4')
            ->andWhere('c.createdAt > :date_min')
            ->andWhere('c.createdAt < :date_max')
            ->andWhere('u.createdAt > :date_min')
            ->andWhere('u.createdAt < :date_max')

            ->setParameter('status_1', $status1)
            ->setParameter('status_2', $status2)
            ->setParameter('status_3', $status3)
            ->setParameter('status_4', $status4)
            ->setParameter('date_max', $dateMax)
            ->setParameter('date_min', $dateMin)

            ->getQuery()->getResult();
    }

    public function getAllCommandsByStatusWitOldhUsers($dateMin, $dateMax, $status1, $status2 = 0, $status3 = 0, $status4 = 0)
    {

        return $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->where('c.status = :status_1 OR c.status = :status_2 OR c.status = :status_3 OR c.status = :status_4')
            ->andWhere('c.createdAt > :date_min')
            ->andWhere('c.createdAt < :date_max')
            ->andWhere('u.createdAt < :date_min')

            ->setParameter('status_1', $status1)
            ->setParameter('status_2', $status2)
            ->setParameter('status_3', $status3)
            ->setParameter('status_4', $status4)
            ->setParameter('date_min', $dateMin)
            ->setParameter('date_max', $dateMax)

            ->getQuery()->getResult();
    }

    // /**
    //  * @return Command[] Returns an array of Command objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Command
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
