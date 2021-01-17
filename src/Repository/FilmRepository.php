<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function rechercherFilmCreeEntreDeuxDates($d1,$d2)
    {
        return $this->createQueryBuilder('m')
                    ->where("m.dateSortie > ?1")
                    ->andWhere("m.dateSortie < ?2")
                    ->setParameter(1, $d1)
                    ->setParameter(2, $d2)
                    ->getQuery()
                    ->getResult();
    }

    public function trouverFilmCreeAvantDate($d1)
    {
        return $this->createQueryBuilder('m')
                    ->where("m.dateSortie < ?1")
                    ->setParameter(1, $d1)
                    ->getQuery()
                    ->getResult();
    }

  
    public function dureeMoyenneGenre($genre)
    {
        return $this->createQueryBuilder('f')
                    ->select('avg(f.duree) as total_duree')
                    ->innerJoin('f.genre','g')
                    ->where('g.nom = :genre')
                    ->setParameter('genre',$genre)
                    ->getQuery()
                    ->getResult();
    }

    public function augmenterNoteFilm($titre){
        return $this->createQueryBuilder('f')
                    ->update()
                    ->set('f.note','f.note + 1')
                    ->where('f.titre = :titre')
                    ->setParameter('titre',$titre)
                    ->getQuery()
                    ->execute();
    }

      public function diminueNoteFilm($titre){
        return $this->createQueryBuilder('f')
                    ->update()
                    ->set('f.note','f.note - 1')
                    ->where('f.titre = :titre')
                    ->setParameter('titre',$titre)
                    ->getQuery()
                    ->execute();
    }

     public function deleteSingleGenre(){
        $query = $em->createQuery('DELETE FROM genre
        WHERE genre.id NOT IN (SELECT film.genre_id
                               FROM film)');

     }

     public function recherchePlusPlus($titre){

            return $this->createQueryBuilder('f')
                        ->where('f.titre LIKE :titre')
                        ->setParameter('titre',"%".$titre."%")
                        ->getQuery()
                        ->getResult();
        }  

     public function augementeFilmAge(Acteur $acteur){
      
        return $this->createQueryBuilder('f')
        ->update()
        ->set('f.ageMinimal','f.ageMinimal + 1')
        ->where('f.acteurs = :acteur')
        ->setParameter('acteur',$acteur)
        ->getQuery()
        ->execute();
     }

 
     //action 17
     public function acteurQuiOntJouerEnsemble($acteur1,$acteur2){

        $qb = $this->createQueryBuilder('f');
        $qb = $qb->Join('f.acteurs', 'a')
                 ->Join('f.acteurs','b')
                 ->where('a.nomPrenom= :acteur1')
                 ->andWhere('b.nomPrenom= :acteur2')
                 ->setParameter('acteur1',$acteur1)
                 ->setParameter('acteur2',$acteur2)
                 ->getQuery()
                 ->getResult();
        return $qb;
    }

    /**
     * {% if age %} -> Age Minimal {{film.ageMinimal}} {% endif %}
     */
    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
