<?php

namespace App\Form;

use App\Entity\Navigations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NavigationsType extends AbstractType
{

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'label' => $this->translator->trans('tName')
      ])
      ->add('link', TextType::class, [
        'label' => $this->translator->trans('tLink')
      ])
      ->add('position', NumberType::class, [
        'label' => $this->translator->trans('tPosition')
      ])
      ->add('authorisation', TextType::class, [
        'label' => $this->translator->trans('tAuthorisation')
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Navigations::class,
    ]);
  }
}
