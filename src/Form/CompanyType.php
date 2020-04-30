<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CompanyType extends AbstractType
{

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [])
            ->add('logo', FileType::class, [
                'data_class' => null,
                'attr' => [
                    'placeholder' => 'user_choose_file',
                ],
                'label_attr' => [
                    'data-browse' => $this->translator->trans('bt_Browse')
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG/JPG',
                    ])
                ]
            ])
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'denomination',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
