<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('Prix')
            // ->add('Photo')
            ->add('photoForm',FileType::class,[
                'mapped'=>false,
                'required'=>false
            ])
            ->add('Stock')
            ->add('categorie',EntityType::class,[
                'class' =>Categories::class,
                'choice_label' => 'nom',
                'label' => 'Choissiez une Categorie'
            ])
            ->add('envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
