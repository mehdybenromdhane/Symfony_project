<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }




    public function findBooksBytitle()
    {

        $query = $this->getEntityManager()
            ->createQuery('SELECT b FROM App\Entity\Book b ORDER BY b.ref DESC');

        return $query->getResult();
    }

    public function findBooksByref()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.ref', 'ASC')
            ->getQuery()
            ->getResult();
    }



    public function showAllBooksByAuthor($name)
    {

        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->addSelect('a')

            ->where('a.username like :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }



    public function updateCategory($old, $new)
    {

        return $this->createQueryBuilder('b')
            ->update()->set('b.category', ':newCat')
            ->setParameter('newCat', $new)

            ->where('b.category like :oldCat')
            ->setParameter('oldCat', $old)

            ->getQuery()
            ->getResult();
    }
}
