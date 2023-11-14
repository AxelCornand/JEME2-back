<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function add(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * Retrieves products for a given category (DQL)
     * @param stings[] $categoryNames
     *
     * @return Products[]
     */
    public function findByCategoryName(array $categoryNames, EntityManagerInterface $entityManager): array
    {
        $products = [];
        // Research for each request
        foreach ($categoryNames as $categoryName) {
            $query = $entityManager->createQuery('SELECT p
                FROM App\Entity\Products p
                INNER JOIN App\Entity\Category c WITH p.id = c.products
                WHERE c.name LIKE :categoryName');
    
            $query->setParameter('categoryName', '%'.$categoryName.'%');
    
            // Execute the query and get the result
            $result = $query->getResult();
    
            // If $products is empty, add the result
            if (empty($products)) {
                $products = $result;
            } else {
                // Remove products that do not have the current category
                foreach ($products as $key => $product) {
                    if (!in_array($product, $result)) {
                        unset($products[$key]);
                    }
                }
            }
        }
        return array_values($products);
    }


//    /**
//     * @return Products[] Returns an array of Products objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
