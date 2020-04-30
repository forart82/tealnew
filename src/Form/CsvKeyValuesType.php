<?php

namespace App\Form;

use App\Entity\CsvKeyValues;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvKeyValuesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('asValue', ChoiceType::class, [
                'choices' => [
                    'First name' => 'email',
                    'Last name' => 'lastname',
                    'Email' => 'email',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'alphabetic' => 'alpha',
                    'numeric' => 'num',
                    'alphanumeric' => 'alphanum',
                    'email' => 'email'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CsvKeyValues::class,
        ]);
    }
}
