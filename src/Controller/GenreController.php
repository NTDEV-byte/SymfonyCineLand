<?php

namespace App\Controller;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class GenreController extends AbstractController
{
    /**
     * action 1
     * @Route("/genre/lister/", name="lister_genre")
     */
	public function lister(){
		$repository = $this->getDoctrine()->getRepository(Genre::class);
		$genres = $repository->findAll();
		return $this->render("genre/listGenre.html.twig", array("genres" => $genres));
	}
     /**
     * action 2
     * @Route("/genre/ajouter", name="ajouter_genre")
     */
    public function ajouter(Request $request){
     $genre = new genre;
     $form = $this->createFormBuilder($genre)
     ->add('nom', TextType::class)
     ->add('ajouter', SubmitType::class)
     ->getForm();
     $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()) {
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($genre);
     $entityManager->flush();
     return $this->render('genre/afficherGenre.html.twig',array('genre' => $genre));
     }

     return $this->render('genre/ajouterGenre.html.twig',
     array('monFormulaire' => $form->createView()));
    }
       /**
         * action 24 
         * @Route("/genre/delete_single_genre",name = "delete_genre")
         */
        public function deleteGenre(){
            $em = $this->getDoctrine()->getManager();
            $requete0 = "SELECT genre.nom FROM genre WHERE id NOT IN (SELECT film.genre_id FROM film)";
            $requete = "DELETE FROM genre
                        WHERE genre.id NOT IN (SELECT film.genre_id FROM film)";
            $resultat0 = $em->getConnection()->query($requete0)->fetchAll();
            if($resultat0 != null){ 
                $res = "";
                foreach($resultat0 as $k=>$v){
                      foreach($v as $k1 => $v2){
                        $res.="Le genre <i>".$v2."</i> n'est jamais utilisé et a été bien supprimé de la liste des genres";
                      }
                      $res.="<br>";
                }
                $resultat = $em->getConnection()->prepare($requete);
                $resultat->execute();
                return new Response("<html><head> <h1 class=\"w3-teal\"> La liste des genres supprimés !</h1><link rel=\"stylesheet\" href=\"../css/w3.css\">".$res."</head></html>");
            }
                return new Response("<html><head><link rel=\"stylesheet\" href=\"../css/w3.css\"><h1 class=\"w3-red\">Tout les genres sont ratachés a des films !</head></html>");
         }
}
