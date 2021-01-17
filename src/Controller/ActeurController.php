<?php

namespace App\Controller;
use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


use App\Form\ListeGenreActeurJouerDansDeuxFilmsType;
use App\Form\DureeTotalActeurType;


class ActeurController extends AbstractController
{
    /**
     * @Route("/cineland/init", name="init")
     */
      public function init()
      {
        $entityManager = $this->getDoctrine()->getManager();

        $acteur1 = new Acteur;
        $acteur1->setNomPrenom("Deneuve Catherine ");
        $acteur1->setdateNaissance(new \DateTime('22-10-1943'));
        $acteur1->setnationalite("france");
          
        $acteur2 = new Acteur;
        $acteur2->setNomPrenom("Depardieu Gérard ");
        $acteur2->setdateNaissance(new \DateTime('27-12-1948'));
        $acteur2->setnationalite("russie");
        
        $acteur3 = new Acteur;
        $acteur3->setNomPrenom("Galabru Michel");
        $acteur3->setdateNaissance(new \DateTime('27-10-1922'));
        $acteur3->setnationalite("france");

        $acteur4 = new Acteur;
        $acteur4->setNomPrenom("Désiré Dupond");
        $acteur4->setdateNaissance(new \DateTime('23-12-2001'));
        $acteur4->setnationalite("groland");
         
        $acteur5 = new Acteur;
        $acteur5->setNomPrenom("Lanvin Gérard");
        $acteur5->setdateNaissance(new \DateTime('21-06-1950'));
        $acteur5->setnationalite("france");

        $genre1 = new Genre;
        $genre1->setNom("animation");

        $genre2 = new Genre;
        $genre2->setNom("policier");

        $genre3 = new Genre;
        $genre3->setNom("drame");

        $genre4 = new Genre;
        $genre4->setNom("comédie");

        $genre5 = new Genre;
        $genre5->setNom("X");
        
        $film1 = new Film;
        $film1->setTitre("Le Dernier Métro");
        $film1->setDuree(131);
        $film1->setDateSortie(new \DateTime('17-09-1980'));
        $film1->setNote(15);
        $film1->setAgeMinimal(12);
        $film1->setGenre($genre3);
        $film1->addActeur($acteur1);
        $film1->addActeur($acteur2);

        $film2 = new Film;
        $film2->setTitre("Astérix aux jeux olympiques");
        $film2->setDuree(117);
        $film2->setDateSortie(new \DateTime('20-01-2008'));
        $film2->setNote(8);
        $film2->setAgeMinimal(0);
        $film2->setGenre($genre1);

        $film3 = new Film;
        $film3->setTitre("Le choix des armes");
        $film3->setDuree(135);
        $film3->setDateSortie(new \DateTime('19-10-1981'));
        $film3->setNote(13);
        $film3->setAgeMinimal(18);
        $film3->setGenre($genre2);
        $film3->addActeur($acteur1);
        $film3->addActeur($acteur2);
        $film3->addActeur($acteur5);

        $film3 = new Film;
        $film3->setTitre("Les Parapluies de Cherbourg");
        $film3->setDuree(91);
        $film3->setDateSortie(new \DateTime('19-02-1964'));
        $film3->setNote(9);
        $film3->setAgeMinimal(0);
        $film3->setGenre($genre3);
        $film3->addActeur($acteur1);

        $film4 = new Film;
        $film4->setTitre("La Guerre des boutons");
        $film4->setDuree(90);
        $film4->setDateSortie(new \DateTime('18-04-1962'));
        $film4->setNote(7);
        $film4->setAgeMinimal(0);
        $film4->setGenre($genre4);
        $film4->addActeur($acteur3);
       
        $entityManager->persist($genre1);

        $entityManager->persist($genre2);
    
        $entityManager->persist($genre3);
   
        $entityManager->persist($genre4);
 
        $entityManager->persist($genre5);

        $entityManager->persist($acteur1);
  
        $entityManager->persist($acteur2);
 
        $entityManager->persist($acteur3);

        $entityManager->persist($acteur4);

        $entityManager->persist($acteur5);
      
        $entityManager->persist($film1);

        $entityManager->persist($film2);
   
        $entityManager->persist($film3);

        $entityManager->persist($film4);

        $entityManager->flush();

        return new Response('<html><head><link rel = "stylesheet" href="../css/w3.css"</head><h1 class="w3-teal">Les données ont été insérés !</h1></html>');
      }
    /**
     *@Route("/" , name="home")
     */
     public function index(){
        return $this->render("base.html.twig");
   }
   /**
    *@Route("/acteur/acteurMenu",name="acteur_menu") 
    */
   public function acteurMenu(){
         $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
         $acteurs  = $repo->findAll();
         return $this->render('acteur/acteurMenu.html.twig',array("acteurs" => $acteurs));
   }
    /**
     * action 3
     * @Route("/acteur/lister", name="lister_acteur")
     */
    public function lister(){
    	 $repository = $this->getDoctrine()->getRepository(Acteur::class);
         $acteurs =  $repository->findAll();
         return $this->render('acteur/listActeur.html.twig', array("acteurs"=> $acteurs));
    }
     /**
     * action 4
     * @Route("/acteur/{id}/afficher", name="afficher_acteur")
     */
public function afficherInformationActeurEtFilms(Acteur $acteur){
    $films = $acteur->getFilms();
    return  $this->render('acteur/afficherActeur.html.twig',array('acteur' => $acteur,
    'films' => $films,
    'afficheFilms' => $films!=null
        ));
}
   /**
     * action 5 et 6
     * @Route("/acteur/ajouter", name="ajouter_acteur")
     * @Route("/acteur/{id}/modifier", name="modifier_acteur")
     */

    public function formAjoutModification(Acteur $acteur = null,Request $request){

        if(!$acteur){
            $acteur = new Acteur;
        }
        $button_text = $acteur->getId()!=null ? 'modifier' : 'ajouter';

     $form = $this->createFormBuilder($acteur)
     ->add('nom_prenom', TextType::class)
     ->add('date_naissance', DateType::class,['days' => range(1,31),'years'=>range(1900,2030)])
     ->add('nationalite', TextType::class)
     ->add($button_text,SubmitType::class)
     ->getForm();

     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($acteur);
         $entityManager->flush();
         
        return $this->render('acteur/afficherActeur.html.twig',array('acteur' => $acteur,
       'afficheFilms' => false,
       ));
       }
    
     return $this->render('acteur/ajoutModifActeur.html.twig',
     array('monFormulaire' => $form->createView(),
           'modifier' => $acteur->getId()!=null
        ));
    }
   /**
     * action 7
     * @Route("/acteur/{id}/supprimer", name="supprimer_acteur")
     */
    public function supprimer(Acteur $acteur){
        $repository = $this->getDoctrine()->getRepository(Acteur::class);
        $em = $this->getDoctrine()->getManager();
        $em->remove($acteur);
        $em->flush();
        return $this->redirectToRoute("acteur_menu");
    }
    /**
     *  action 16
     * @Route("/acteur/jouerDansPlusDeTroisFilm", name="afficher_acteur_jouer_plus_trois_film")
     */
    public function acteurQuiOntJouerDansPlusDeTroisFilm(){
            $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
            $acteurs = $repo->acteurQuiOntJouerDansPlusDeTroisFilm();
            return $this->render("/acteur/acteurPlusTrois.html.twig",array("acteurs"=>$acteurs));
    }
    /**
     * action 18
     * @Route("/acteur/listeGenreActeur",name = "liste_genre_acteur")
     */
     public function listGenreActeurJouerDansAuMoinsDeuxFilms(Request $request){
        $form = $this->createForm(ListeGenreActeurJouerDansDeuxFilmsType::class);
        $form->handleRequest($request);
        $genres = null;
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        if($form->isSubmitted() && $form->isValid()){
               $data = $form->getData();
               $acteur =  $data['nom'];
               $genres = $repo->listerLesGenreAuMoinsDeuxFilm($acteur);
        }
        return $this->render('acteur/listGenreActeurJouerDansAuMoinsDeuxFilms.html.twig',
        array("form" => $form->createView(),
        "genres" => $genres
        ));
     }

    /**
     * action 19 
     * @Route("/acteur/duree_total_jouer", name="duree_total_jouer")
     */
     public function dureeTotalJouer(Request $request){ 
        $form = $this->createForm(DureeTotalActeurType::class);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

        $duree = null;
        $resultat = false;
        if($form->isSubmitted() && $form->isValid()){
               $data = $form->getData();
               $acteur =  $data['Acteur'];
               $res = $repo->dureeTotalJouerParActeur($acteur);
               $duree = $res['0']['total'];
               $resultat = true;
        }
            return $this->render("acteur/acteurTotalDuree.html.twig",array('duree' => $duree,
            'form' => $form->createView(), 'resultat'  => $resultat));
     }
     /**
      *action 20
      *@Route("/acteur/list_films_jouer",name="list_film_ordonnee")
      *
      */
      public function listFilmJouerOrdonne(){
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        $resultat = $repo->listFilmDansLequelActeurAjouer();
       return $this->render("acteur/listFilmJouerOrdonne.html.twig", array('resultat'=> $resultat));
      }
      /**
       * action 21 
       * @Route("/acteur/affiche_liste_genre" , name="affiche_liste_genre")
       */
       public function listGenreFilmJouer(){

         $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

         $genres = $repo->listGenreFilmDansLequelActeurAjouer();

        return $this->render("acteur/listTab.html.twig",array("genres" => $genres));
    
      
       }
}