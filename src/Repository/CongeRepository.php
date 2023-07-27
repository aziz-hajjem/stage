<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conge>
 *
 * @method Conge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conge[]    findAll()
 * @method Conge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
    }

    public function validateConge($conge){
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("
            UPDATE App\Entity\Conge b SET b.statut_conge = 'Accepté' WHERE b.id = :conge 

        ")
        ->setParameter('conge',$conge)
        ;
        return $query->getResult();
    }
    public function NoValidateConge($conge){
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("
            UPDATE App\Entity\Conge b SET b.statut_conge = 'Refusé' WHERE b.id = :conge 

        ")
        ->setParameter('conge',$conge)
        ;
        return $query->getResult();
    }
    public function getMesConges($user){
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("
            SELECT b
            FROM App\Entity\Conge b
            where b.user = :user
            
        ")
        ->setParameter('user',$user)
        ;
        return $query->getResult();
    }

//    /**
//     * @return Conge[] Returns an array of Conge objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conge
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
