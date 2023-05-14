<?php

namespace App\Repository;

use App\Entity\ActivityMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityMember>
 *
 * @method ActivityMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityMember[]    findAll()
 * @method ActivityMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityMember::class);
    }

    public function save(ActivityMember $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivityMember $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByMemberId(int $memberId): array
    {
        return $this->createQueryBuilder('am')
            ->andWhere('am.member = :memberId')
            ->setParameter('memberId', $memberId)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return ActivityMember[] Returns an array of ActivityMember objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityMember
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
