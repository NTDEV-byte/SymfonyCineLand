<?php

namespace App\Repository;

use App\Entity\Acteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acteur[]    findAll()
 * @method Acteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acteur::class);
    }
    
    public function acteurQuiOntJouerDansPlusDeTroisFilm(){
        $qb = $this->createQueryBuilder('a');
        $qb = $qb ->innerJoin('a.films','f')
                  ->having('count(f) > 2')
                  ->groupBy('a');
            return $qb->getQuery()->getResult();
    }

    public function listerLesGenreAuMoinsDeuxFilm($acteur){
        $qb = $this->createQueryBuilder('a');
        $qb = $qb  ->select('g.nom , count(g.nom) as total_genre')
                   ->innerJoin('a.films','f')
                   ->innerJoin('f.genre','g')
                   ->having('total_genre > 1')
                   ->where('a.nomPrenom = :acteur')
                   ->setParameter('acteur',$acteur);
            return $qb->getQuery()->getResult();
    }

    public function dureeTotalJouerParActeur($acteur){

            $qb = $this->createQueryBuilder('a')
                       ->select('sum(f.duree) as total')
                       ->innerJoin('a.films','f')
                       ->where('a.nomPrenom = :acteur')
                       ->setParameter('acteur',$acteur)
                       ->getQuery();
               return  $qb->getResult();
    }

    public function listFilmDansLequelActeurAjouer(){

            $qb = $this->createQueryBuilder('a')
                    ->select('a.nomPrenom , f.titre , f.dateSortie')
                    ->innerJoin('a.films','f')
                    ->orderBy('a.nomPrenom,f.dateSortie')
                    ->getQuery();
                return  $qb->getResult();
    }
    
    public function listGenreFilmDansLequelActeurAjouer(){

        $qb = $this->createQueryBuilder('a')
                ->select('distinct a.nomPrenom , g.nom')
                ->innerJoin('a.films','f')
                ->innerJoin('f.genre','g')
                ->orderBy('a.nomPrenom,g.nom')
                ->getQuery();
            return  $qb->getResult();
    }

    // /**
    //  * @return Acteur[] Returns an array of Acteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Acteur
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
