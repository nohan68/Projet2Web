<?php

namespace App\Repository;

use App\Entity\PanierPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method PanierPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierPlace[]    findAll()
 * @method PanierPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierPlace::class);
    }

    // /**
    //  * @return PanierPlace[] Returns an array of PanierPlace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PanierPlace
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findEvenements($user){
        //select evenement.nom from panier_place left join evenement on evenement.id = panier_place.evenement_id where panier_place.user_id=5;
        $evenements = $this->createQueryBuilder('p');

        $evenements->select('e.nom', 'e.prix','e.photo')
                    ->from('App\Entity\PanierPlace','panier')
                    ->leftJoin('App\Entity\Evenement', 'e', Join::WITH,'p.evenement=e')
                    ->where('panier.user=?1')
                    ->setParameter(1, $user);
        $evenements = $evenements->getQuery()->getResult();
        dump($evenements);
        return $evenements;

    }
}
