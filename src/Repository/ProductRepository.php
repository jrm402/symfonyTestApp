<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

	public function getAllProducts(): array
	{
		$em = $this->getEntityManager();
		$stmt = $em->getConnection()->prepare('select * from product');
		$stmt->execute();

		$res = $stmt->fetchAll();

		return $res;
	}

	public function getProduct($id): ?array
	{
		$em = $this->getEntityManager();
		$stmt = $em->getConnection()->prepare('select * from product where id = :id');
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		$res = $stmt->fetch();
		if($res)
			return $res;

		return null;
	}

	public function saveProduct($product): void
	{
		$em = $this->getEntityManager();
		if(isset($product['id'])) {
			// Update
			$stmt = $em->getConnection()->prepare('update product set name = :name, description = :description where id = :id');
			$stmt->bindValue(':id', $product['id']);
			$stmt->bindValue(':name', $product['name']);
			$stmt->bindValue(':description', $product['description']);
			$stmt->execute();
		}
		else {
			// Insert
			$stmt = $em->getConnection()->prepare('insert into product (name, description) values (:name, :description)');
			$stmt->bindValue(':name', $product['name']);
			$stmt->bindValue(':description', $product['description']);
			$stmt->execute();
		}
	}

	public function deleteProduct($id): void
	{
		$em = $this->getEntityManager();
		$stmt = $em->getConnection()->prepare('delete from product where id = :id');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
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
