<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Bonplan name',
                ],
                
            ])
            ->add('nom', null , [
                'attr' => [
                    'class' => 'form-control',
                ],
                
            ])
            ->add('prenom', null , [
                'attr' => [
                    'class' => 'form-control',
                ],
                
            ])
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    
                ],
                
            ])
            ->add('image', FileType::class, [
                'label' => 'imageFile',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png',
                    'class' => 'form-control form-choose',
                    
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG, JPEG or PNG image',
                    ])
                ],
            ])
            ->add('num_tel', null , [
                'attr' => [
                    'class' => 'form-control',
                ],
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
