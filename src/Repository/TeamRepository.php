<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Team::class);
        $this->manager = $manager;
    }

    public function add(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addToTeam($name,$sportId) : Team
    {
        $team = new Team();
        $team -> setName($name)
              -> setSportId($sportId);

        $this->manager->persist($team);
        $this->manager->flush();
        
        return $team;
    }

    public function removeTeam(Team $team)
    {
        $this->manager->remove($team);
        $this->manager->flush();
    }

    public function updateTeam(Team $team) : Team
    {
        $this->manager->persist($team);
        $this->manager->flush(); 

        return $team;
    }

    public function getTeamPlayers($id): ?Team
   {
       return $this->createQueryBuilder('t')
           ->innerJoin('t.players','p')
           ->addSelect('p')
           ->andWhere('t.id = :id')
           ->setParameter('id', $id)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

//    /**
//     * @return Team[] Returns an array of Team objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Team
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
