<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clients>
 *
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    public function save(Clients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Clients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getClientInfoForList()
    {
        $entityManager = $this->getEntityManager();
        $dql= "SELECT 
        U.email, 
        U.name AS user_name, 
        U.image AS user_image, 
        U.phone,
        C.activity,
        MAX(CASE WHEN CS.date_session <= CURRENT_DATE() THEN CS.date_session ELSE '' END) AS last_session_date
    FROM 
        App\Entity\ClientsCoachingSession CCS
    INNER JOIN 
        App\Entity\CoachingSession CS WITH CCS.coachingSessionId = CS.id
    INNER JOIN 
        App\Entity\Clients C WITH C.id = CCS.clientId
    INNER JOIN 
        App\Entity\User U WITH U.userclient = C.id
    GROUP BY 
        U.email, U.name, U.image, U.phone, C.activity";

        $query = $entityManager->createQuery($dql);
        $res = $query->getResult();
        foreach ($res as &$row) {
            $row['activity'] = explode('|', $row['activity']);
        }
        return $res;
    }

    //    /**
    //     * @return Clients[] Returns an array of Clients objects
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

    //    public function findOneBySomeField($value): ?Clients
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
