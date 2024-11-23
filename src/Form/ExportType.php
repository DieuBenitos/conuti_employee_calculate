<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class ExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('format', ChoiceType::class, [
                'choices' => [
                    'xlsx' => 'xlsx',
                    'ods' => 'ods',
                    'csv' => 'csv',
                ],
                'label' => false,
                'placeholder' => 'Select a format',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Exportieren',
                'attr' => [
                    'class' => 'btn btn-conuti float-end'
                ],
            ])
        ;
    }
}
