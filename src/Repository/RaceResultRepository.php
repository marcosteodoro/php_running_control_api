<?php

namespace App\Repository;

use App\Entity\Race;
use App\Entity\RaceResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceResult[]    findAll()
 * @method RaceResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceResult::class);
    }

    public function getGeneralRankingByRace(Race $race): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                runner.id,
                (YEAR(CURRENT_TIMESTAMP) - YEAR(runner.birthdate)) AS age,
                runner.name
            FROM 
                race_result
                INNER JOIN runner ON runner.id = race_result.runner_id
            WHERE 
                race_id = :race_id
            ORDER BY 
                race_result.finish_time
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['race_id' => $race->getId()]);

        return $stmt->fetchAllAssociative();
    }
   
    public function getGeneralRankingByRaceAndAge(Race $race, int $ageMin, ?int $ageMax = 200): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                runner.id,
                (YEAR(CURRENT_TIMESTAMP) - YEAR(runner.birthdate)) AS age,
                runner.name
            FROM 
                race_result
                INNER JOIN runner ON runner.id = race_result.runner_id
            WHERE 
                race_id = :race_id
            HAVING
                age BETWEEN :age_min AND :age_max

            ORDER BY 
                race_result.finish_time
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'race_id' => $race->getId(),
            'age_min' => $ageMin,
            'age_max' => $ageMax,
        ]);

        return $stmt->fetchAllAssociative();
    }

    // /**
    //  * @return RaceResult[] Returns an array of RaceResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RaceResult
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
