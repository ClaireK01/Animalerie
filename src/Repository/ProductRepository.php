<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
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
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getLastProduct(){
        return $this->createQueryBuilder('p')
                    ->where('p.isActif = 1')
                    ->orderBy('p.id', 'DESC')
                    ->setMaxResults(6)
                    ->getQuery()->getResult();
    }

    public function getCheapestProduct(){
        return $this->createQueryBuilder('p')
                    ->where('p.isActif = 1')
                    ->orderBy('p.price', 'ASC')
                    ->setMaxResults(6)
                    ->getQuery()->getResult();
    }

    public function getBestSells(){
        return $this->createQueryBuilder('p')
                    ->select('p, COUNT(c) as counted')
                    ->join('p.commands', 'c')
                    ->where('c.status = 200 OR c.status = 300')
                    ->orderBy('counted', 'DESC')
                    ->groupBy('p.id')
                    ->setMaxResults(6)
                    ->getQuery()->getResult();
    }

    public function getSameBrandProducts($brand){
        return $this->createQueryBuilder('p')
                ->where('p.brand = :brand')
                ->setParameter('brand', $brand)
                ->setMaxResults(6)
                ->getQuery()->getResult();
    }

    public function getSimilarProducts($label, $categories){
        return $this->createQueryBuilder('p')
                    ->join('p.categories', 'c')
                    ->where('p.label LIKE :label OR c IN (:categories)')
                    ->setParameter('label', '%'.$label.'%')
                    ->setParameter('categories', $categories)
                    ->setMaxResults(6)
                    ->getQuery()->getResult();
    }

    


    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
