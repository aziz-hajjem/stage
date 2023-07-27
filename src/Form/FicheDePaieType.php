<?php

namespace App\Form;

use App\Entity\FicheDePaie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheDePaieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('salaire_de_base')
            ->add('prime_de_presence')
            ->add('prime_de_rendement')
            ->add('retenue_cnrps')
            ->add('deduction_situation_familiale')
            ->add('autre_deduction')
            ->add('avance')
            ->add('heures_supplimentaires')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheDePaie::class,
        ]);
    }
}
