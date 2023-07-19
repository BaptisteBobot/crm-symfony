<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }
    public function getMonthName(int $monthNumber): string
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra'); 
        return strftime('%B', mktime(0, 0, 0, $monthNumber));
    }

    public function findMonthlyUserCountForCurrentYear(): array
    {
        $conn = $this->getEntityManager()->getConnection();
    
        $sql = '
            SELECT MONTH(u.created_at) as month, COUNT(u.id) as count 
            FROM `user` u 
            WHERE YEAR(u.created_at) = :year
            GROUP BY month
            ORDER BY month ASC
        ';
    
        $stmt = $conn->executeQuery($sql, ['year' => date('Y')]);
        $userCountsByMonth = $stmt->fetchAllAssociative();
    
        $results = [
            'janvier' => 0, 'février' => 0, 'mars' => 0, 'avril' => 0, 
            'mai' => 0, 'juin' => 0, 'juillet' => 0, 'août' => 0, 
            'septembre' => 0, 'octobre' => 0, 'novembre' => 0, 'décembre' => 0,
        ];
    
        foreach ($userCountsByMonth as $userCount) {
            $monthName = $this->getMonthName($userCount['month']);
            $results[$monthName] = $userCount['count'];
        }
    
        $finalResults = [];
        foreach ($results as $month => $count) {
            $finalResults[] = ['month' => $month, 'count' => $count];
        }
    
        return $finalResults;
    }
    
    
    
    
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
