<?php

namespace App\Form;

use App\Entity\CsvKeyValues;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CsvKeyValuesType extends AbstractType
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
            'label' => $this->translator->trans('tName'),
            ])
            ->add('asValue', ChoiceType::class, [
                'label' => $this->translator->trans('tAsValue'),
                'choices' => [
                    'First name' => 'email',
                    'Last name' => 'lastname',
                    'Email' => 'email',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => $this->translator->trans('tType'),
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
