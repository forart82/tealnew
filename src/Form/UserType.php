<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Language;


class UserType extends AbstractType
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
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('tEmail'),
            ])
            ->add('roles', ChoiceType::class, [
                'label' => $this->translator->trans('tRoles'),
                'choices' => [
                    'User' => '[ROLE_USER]',
                    'Admin' => '[ROLE_ADMIN]',
                    'Super Admin' => '[ROLE_SUPER_ADMIN]',
                ],
                'multiple'=>true,
                'expanded'=>true,
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
            ])
            ->add('language', EntityType::class, [
                'label' => $this->translator->trans('tLanguage'),
                'class' => Language::class,
                'choice_label' => 'denomination',
            ])
            ->add('isNew', ChoiceType::class, [
                'label' => $this->translator->trans('tIsNew'),
                'choices' => [
                    $this->translator->trans('tYes') => true,
                    $this->translator->trans('tNo') => false,
                ]
            ])
            ->add('company', EntityType::class, [
                'label' => $this->translator->trans('tCompany'),
                'class' => Company::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
