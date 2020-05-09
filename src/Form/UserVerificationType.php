<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UserVerificationType extends AbstractType
{
  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstname', TextType::class, [
        'label' => $this->translator->trans('tFirstname'),
      ])
      ->add('lastname', TextType::class, [
        'label' => $this->translator->trans('tLastname'),
      ])
      ->add('password', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'The password fields must match.',
        'required' => true,
        'first_options'  => [
          'label' => $this->translator->trans('tFirstPassword'),
        ],
        'second_options' => [
          'label' => $this->translator->trans('tSecondPassword'),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
