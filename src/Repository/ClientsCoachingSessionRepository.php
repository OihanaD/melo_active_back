<?php

namespace App\Repository;

use ApiPlatform\Api\QueryParameterValidator\Validator\Length;
use App\Entity\ClientsCoachingSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientsCoachingSession>
 *
 * @method ClientsCoachingSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientsCoachingSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientsCoachingSession[]    findAll()
 * @method ClientsCoachingSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsCoachingSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientsCoachingSession::class);
    }

    public function save(ClientsCoachingSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClientsCoachingSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function search($year, $month, $firstday)
    {

        $lastday = $firstday + 6;

        $dql = 'SELECT 
                    U.email,U.name,U.id AS user_id, U.password, U.image AS user_image, U.address,
                    C.activity, C.objectives, C.problems, C.repetition_per_month,
                    CCS.is_paid, CS.price, 
                    SUBSTRING(CS.date_session, 1, 4) AS year, 
                    SUBSTRING(CS.date_session, 6, 2) AS month, 
                    SUBSTRING(CS.date_session, 9, 2) AS day, 
                    SUBSTRING(CS.date_session, 12, 5) AS time,
                    CS.recap_of_coaching,CS.activity_session, COACH.information, COACH.id AS coach_id
                FROM 
                    App\Entity\User U
                INNER JOIN 
                    App\Entity\Clients C WITH C.id = U.userclient
                INNER JOIN 
                    App\Entity\ClientsCoachingSession CCS WITH C.id = CCS.clientId
                INNER JOIN 
                    App\Entity\CoachingSession CS WITH CCS.coachingSessionId = CS.id
                INNER JOIN 
                    App\Entity\Coach COACH WITH COACH.id = CS.coach
                HAVING
                   year = :year 
                    AND month = :month 
                    AND day BETWEEN :firstday AND :lastday
                ORDER BY
                    time
                   ';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('year', $year);
        $query->setParameter('month', $month);
        $query->setParameter('lastday', $lastday);
        $query->setParameter('firstday', $firstday);

        return $query->getResult();
    }
    public function payments()
    {
        $dql = 'SELECT U.name AS user_name, CS.id AS session_id, CS.date_session, CS.price, CCS.is_paid, CS.activity_session
        FROM App\Entity\ClientsCoachingSession AS CCS
        LEFT JOIN App\Entity\CoachingSession CS WITH CCS.coachingSessionId = CS.id
        INNER JOIN App\Entity\Clients C WITH C.id = CCS.clientId
        LEFT JOIN App\Entity\User U WITH U.userclient = C.id
        WHERE CS.date_session <= CURRENT_DATE() AND U.userclient IS NOT NULL
        ORDER BY CS.date_session DESC';


        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
    public function paymentsPerMonthPayed($month, $year)
    {
       

        $dql = 'SELECT 
        SUM(CS.price) AS total_paid_amount
    FROM 
        App\Entity\ClientsCoachingSession CCS
    INNER JOIN 
        App\Entity\CoachingSession CS WITH CCS.coachingSessionId = CS.id
    WHERE 
        CCS.is_paid = true
        AND SUBSTRING(CS.date_session, 1, 4) = :year
        AND SUBSTRING(CS.date_session, 6, 2) = :month
        AND CS.date_session<= CURRENT_DATE()';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('month', $month);
        $query->setParameter('year', $year);

        return $query->getResult();
    }
    public function paymentsWaiting()
    {
        $dql = 'SELECT 
            SUM(CS.price) AS total_wait_amount
        FROM 
            App\Entity\ClientsCoachingSession CCS
        INNER JOIN 
            App\Entity\CoachingSession CS WITH CCS.coachingSessionId = CS.id
        WHERE 
            CCS.is_paid = false
            AND CS.date_session<= CURRENT_DATE()';

        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }

    public function getClientDataById($clientId)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT 
        U.email, U.name AS user_name, U.image AS user_image, U.address,U.phone,
        C.activity, C.objectives, C.problems, C.repetition_per_month,
        GROUP_CONCAT(
            CONCAT(
                'Session: ', CS.id,
                ', Price: ', CS.price,
                ', Date: ', CS.date_session,
                ', Activity: ', CS.activity_session,
                ', Recap: ', COALESCE(CS.recap_of_coaching, ''),
                ', Objectif: ', COALESCE(CS.objectif_of_coaching, ''),
                ', Ispaid: ', CCS.is_paid
            )
            ORDER BY CS.date_session DESC SEPARATOR '||'
        ) AS session_list,
        SUM(CASE WHEN CCS.is_paid = true THEN CS.price ELSE 0 END) AS total_paid,
        SUM(CASE WHEN CCS.is_paid = false THEN CS.price ELSE 0 END) AS total_unpaid
    FROM 
        App\Entity\ClientsCoachingSession CCS
    INNER JOIN 
        App\Entity\CoachingSession CS WITH CCS.coachingSessionId = CS.id
    INNER JOIN 
        App\Entity\Clients C WITH C.id = CCS.clientId
    INNER JOIN 
        App\Entity\User U WITH U.userclient = C.id
    WHERE 
        C.id = :clientId
    GROUP BY 
        U.email, U.name, U.image, U.address,
        C.activity, C.objectives, C.problems, C.repetition_per_month";

        $userClientQuery = $entityManager->createQuery($dql)->setParameter('clientId', $clientId);

        $res = $userClientQuery->getResult();
        //Pour chaque valeur modifié directement avec le &
        foreach ($res as &$row) {
            //Coupez le session list à l'endroit des ||
            $row['session_list'] = explode('||', $row['session_list']);
            //Initialisation du tableau
            $sessions = [];
            foreach ($row['session_list'] as $index => $sessionString) {
                // Divisez chaque session en un tableau en utilisant ', ' comme délimiteur
                $sessionParts = explode(', ', $sessionString);

                // Initialisez un tableau pour stocker les paires clé-valeur
                $sessionArray = [];

                foreach ($sessionParts as $part) {
                    // Divisez chaque élément en clé et en valeur en utilisant ": " comme séparateur
                    list($key, $value) = explode(': ', $part, 2);

                    // Ajoutez la paire clé-valeur au tableau de la session
                    $sessionArray[$key] = $value;
                }

                // Ajoutez la session au tableau de sessions avec un index
                $sessions[$index] = $sessionArray;
            }

            // Mettez à jour la valeur de 'session_list' dans $row avec le tableau de sessions
            $row['session_list'] = $sessions;
            $row['activity'] = explode('|', $row['activity']);
        }

        return $res;
    }
   


    //    /**
    //     * @return ClientsCoachingSession[] Returns an array of ClientsCoachingSession objects
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

    //    public function findOneBySomeField($value): ?ClientsCoachingSession
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
