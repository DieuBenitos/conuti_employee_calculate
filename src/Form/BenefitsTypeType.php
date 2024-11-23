<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BenefitsTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'app.benefits',
                    'attr' => [
                        'placeholder' => 'app.benefits'
                    ]
                ]
            )
            ->add('monetaryBenefit', CheckboxType::class, [
                    'label' => 'Ist geldwerter Vorteil',
                    'required' => false,
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'Speichern',
                'attr' => [
                    'class' => 'btn-conuti float-end'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\BenefitsType::class
        ]);
    }
}
