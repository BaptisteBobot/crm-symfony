<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 *
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function save(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findMonthlyActivityCountForCurrentYear(): array
    {
        $conn = $this->getEntityManager()->getConnection();
    
        $sql = "
        SELECT DATE_FORMAT(a.start_date, '%Y-%m') as month, COUNT(a.id) as count
        FROM activity a
        WHERE YEAR(a.start_date) = YEAR(CURRENT_DATE())
        GROUP BY month
        ORDER BY month ASC
    ";
    
        $stmt = $conn->executeQuery($sql, ['year' => date('Y')]);
        $activityCountsByMonth = $stmt->fetchAllAssociative();
    
        // Initialisez le tableau de résultats avec tous les mois de l'année
        $results = [
            'janvier' => 0, 'février' => 0, 'mars' => 0, 'avril' => 0, 
            'mai' => 0, 'juin' => 0, 'juillet' => 0, 'août' => 0, 
            'septembre' => 0, 'octobre' => 0, 'novembre' => 0, 'décembre' => 0,
        ];
    
        // Ajoutez les comptes d'activités aux mois appropriés
        foreach ($activityCountsByMonth as $activityCount) {
            // Explodez la chaîne de caractères 'month' pour obtenir le numéro du mois
            list($year, $month) = explode('-', $activityCount['month']);
    
            // Convertissez le numéro du mois en nom de mois
            $monthName = $this->getMonthName(intval($month));
            $results[$monthName] = $activityCount['count'];
        }
    
        // Transformez le tableau des résultats pour correspondre à la structure attendue par le graphique
        $finalResults = [];
        foreach ($results as $month => $count) {
            $finalResults[] = ['month' => $month, 'count' => $count];
        }
    
        return $finalResults;
    }
    
    public function getMonthName(int $monthNumber): string
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra'); 
        return strftime('%B', mktime(0, 0, 0, $monthNumber));
    }
    


//    /**
//     * @return Activity[] Returns an array of Activity objects
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

//    public function findOneBySomeField($value): ?Activity
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
