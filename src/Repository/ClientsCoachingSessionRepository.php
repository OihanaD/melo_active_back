<?php

namespace App\Repository;

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
    public function search($year,$month, $firstday){
       
        $lastday = $firstday + 6;

        $dql = 'SELECT 
                    U.email,U.name, U.password, U.image AS user_name, U.image AS user_image, U.address,
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
                   ';

            $query = $this->getEntityManager()->createQuery($dql);
            $query->setParameter('year', $year);
            $query->setParameter('month', $month);
            $query->setParameter('lastday', $lastday);
            $query->setParameter('firstday', $firstday);

            return $query->getResult(); 
           
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
