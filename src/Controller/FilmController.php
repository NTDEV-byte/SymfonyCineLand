<?php

namespace App\Controller;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Acteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


use App\Form\FilmType;
use App\Form\ListEntreDeuxDatesType;
use App\Form\ListDateAnterieurType;
use App\Form\RechercheAvanceeType;
use App\Form\DureeMoyenneType;
use App\Form\ActeurJouerEnsembleType;
use App\Form\AugmenterAgeFilmType;



class FilmController extends AbstractController
{
    
      /**
       * @Route("/film/menu" , name="film_menu")
       */
      public function menu(){ 
        $films = $this->getDoctrine()->getRepository(Film::class)->findAll();
        return $this->render('film/menuFilm.html.twig', array('films' => $films));
    }

    /**
     * action 8
     * @Route("/film/lister", name="lister_film")
     */
    public function lister(){
    	$repository = $this->getDoctrine()->getRepository(Film::class);
    	$films = $repository->findAll();
        return $this->render('film/listFilm.html.twig', array("films"=> $films));
    }

     /**
      * action 9
     * @Route("/film/{id}/afficher", name="afficher_film")
     */
    public function afficherInformationFilmEtActeur(Film $film){

      $acteurs = $film->getActeurs(); 
 
     return  $this->render('film/afficherFilm.html.twig',array('film' => $film,
     'acteurs' => $acteurs,
     'afficheActeur' => $acteurs!=null
         ));
    }
    /**
     * action 10 et 11
     * @Route("/film/ajouter", name="ajouter_film")
     * @Route("/film/{id}/modifier" , name="modifier_film")
     */

    public function formAjoutModification(Film $film = null,Request $request){

            if(!$film){
              $film = new Film;
            }

     $button_texte = $film->getId() != null ? 'modifier' : 'ajouter';
            
     $form = $this->createForm(FilmType::class,$film);
     $form->add($button_texte,SubmitType::class ,['attr' =>  ['class' => 'w3-btn w3-green'] ]);
     $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()){
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($film);
     $entityManager->flush();
     return $this->render('film/afficherFilm.html.twig',array('film' => $film,
        'afficheActeur' => false    
    ));
     }
     
     return $this->render('film/ajoutModiffilm.html.twig',
     array('monFormulaire' => $form->createView(),
           'modifier' => $film->getId() != null
        ));
    }

   /**
     * action 12
     * @Route("/film/{id}/supprimer", name="supprimer_film")
     */
    public function supprimer(Film $film){
        $repository = $this->getDoctrine()->getRepository(Film::class);
        $em =   $this->getDoctrine()->getManager();
        $em->remove($film);
        $em->flush();
        return $this->redirectToRoute("film_menu");
    }

   /**
    * action 13
     * @Route("/film/listeFilmED", name="lister_film_entre_dates")
     */
    public function listerEntreDeuxDate(Request $request){
            $form = $this->createForm(ListEntreDeuxDatesType::class);
            $form->handleRequest($request);


            $films = null;

            if($form->isSubmitted() && $form->isValid()){
                   $data = $form->getData();
                   $date1 = $data['date1'];
                   $date2  = $data['date2'];

                   $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
                 
                   $films = $repo->rechercherFilmCreeEntreDeuxDates($date1,$date2);

            }

            return $this->render("film/listeFilm.html.twig",
            array("form" => $form->createView(),
                  "films" => $films
            ));
    }

    /**
     * action 14
     * @Route("/film/listeFilmAD", name="lister_film_avant_date")
     */
    public function listerDateAnterieur(Request $request){
        $form = $this->createForm(ListDateAnterieurType::class);
        $form->handleRequest($request);

        $films = null;

        if($form->isSubmitted() && $form->isValid()){
               $data = $form->getData();
               $date1 = $data['date1'];

               $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
             
               $films = $repo->trouverFilmCreeAvantDate($date1);

        }
        return $this->render("film/listeFilm.html.twig",
        array("form" => $form->createView(),
              "films" => $films
        ));
    }
        
   /**
   * action 15
   * @Route("/film/acteursJouerDansFilm" , name="list_acteurs")
   */
  public function afficheLesActeursQuiOntJouer(Request $request){
    $titre = $request->request->get('titre');
        if($request->request->count() > 0){
            $film  = $this->getDoctrine()->getManager()->getRepository(Film::class)->findOneBy(array('titre' => $titre));
            dump($film);
            $acteurs =  $film->getActeurs();
          
            return $this->render("film/acteurJouerDansFilm.html.twig",array("acteurs" => $acteurs,
             "titre" => $titre
            ));
        }
        return $this->render("/film/afficheLesActeursDuFilm.html.twig");
  }
    /**
     * action17
     * @Route("/film/ActeurjouerEnsemble",name="afficher_jouer_ensemble")
     */
    public function acteurQuiOntJouerEnsembleDansUnFilm(Request $request){
        $form = $this->createForm(ActeurJouerEnsembleType::class);
        $form->handleRequest($request);
        $films = null;
        if($form->isSubmitted() && $form->isValid()){
               $data = $form->getData();
               $acteur1 = $data['acteur1'];
               $acteur2  = $data['acteur2'];

               $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
             
               $films = $repo->acteurQuiOntJouerEnsemble($acteur1,$acteur2);
        }
        return $this->render("film/listeFilm.html.twig",
        array("form" => $form->createView(),
              "films" => $films
        ));
        }
       /**
        * action 22
        * @Route("/film/affiche_duree_moyenne", name = "affiche_duree_moyenne_genre")
        */
        public function dureeMoyenneGenre(Request $request){
            $form = $this->createForm(DureeMoyenneType::class);
            $form->handleRequest($request);
            $duree = null;
            $genre = null;
            if($form->isSubmitted() && $form->isValid()){
                   $data = $form->getData();
                   $genre = $data['genre'];
                   $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
                   $res = $repo->dureeMoyenneGenre($genre);
                   $duree = $res['0']['total_duree'];

            }

            return $this->render("film/dureeMoyenneGenre.html.twig",
            array("form" => $form->createView(),
                  "duree" => $duree,
                  "genre" => $genre
            ));
        }
        /**
         * action 23
         * @Route("/film/augmenterDiminuerNote", name="augmenter_diminuer_note")
         */
        public function augmenterDiminuerNote(Request $request){
            $titre = $request->request->get("titre");
            $boutton = $request->request->get("boutton");
       
            $repo  = $this->getDoctrine()->getRepository(Film::class);
            
            if($request->request->count() > 0){
            
                if($boutton == "+"){
                    $res = $repo->augmenterNoteFilm($titre);
                }else{
                    $res = $repo->diminueNoteFilm($titre);
                }
            }
            return $this->render("film/augmenterDiminuerNote.html.twig");
        }
         /**
          *action 25
          *@Route("/film/recherche_avancer" , name="recherche_avancer")
          */
         public function rechercheAvancee(Request $request){
            $form = $this->createForm(RechercheAvanceeType::class);
            $form->handleRequest($request);


            $films = null;

            if($form->isSubmitted() && $form->isValid()){
                   $data = $form->getData();
                   $titre = $data['titre'];

                   $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
                 
                   $films = $repo->recherchePlusPlus($titre);

            }

            return $this->render("film/listeFilm.html.twig",
            array("form" => $form->createView(),
                  "films" => $films
            ));
         }
         /**
          *action 26
          *@Route("film/augmente_film_age" , name = "augmente_film_age")
          */
         public function augmenteAgeFilmsActeurDonne(Request $request){
            $form = $this->createForm(AugmenterAgeFilmType::class);
            $form->handleRequest($request);
    
            $films = null;
    
            if($form->isSubmitted() && $form->isValid()){
                   $data = $form->getData();
                   $nom =  $data['nom'];
                   $step = $data['step'];
                   if($step == null) $step = 1;
                   
                   $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
                   $acteur = $repo->findOneBy(array('nomPrenom'=>$nom));
                   $films = $acteur->getFilms();
                    
                   $manager = $this->getDoctrine()->getManager();
                   foreach($films as $film){ 
                        $film->setAgeMinimal($film->getAgeMinimal() + $step);
                        $manager->persist($film);
                   }
                   $manager->flush();
            }
            return $this->render("film/listeFilm.html.twig",
            array("form" => $form->createView(),
                  "films" => $films,
                  "age" => true
            ));
         }
}
?>
