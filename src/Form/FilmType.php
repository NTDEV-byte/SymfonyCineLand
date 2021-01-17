<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


use App\Entity\Film;
use App\Entity\Genre;

class FilmType extends AbstractType {



    public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add("genre",EntityType::class,array('class' => Genre::class))
    ->add('titre', TextType::class)
    ->add('duree', IntegerType::class)
    ->add('date_sortie', DateType::class,['days' => range(1,31),'years'=>range(1900,2030)])
    ->add('note',IntegerType::class)
    ->add('age_minimal',IntegerType::class);
    }
    


    public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
    'data_class' => Film::class,
    ));
    }

}

?>