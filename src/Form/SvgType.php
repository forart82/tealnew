<?php

namespace App\Form;

use App\Entity\Svg;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SvgType extends AbstractType
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('tName'),
            ])
            ->add('svg', FileType::class, [
                'label' => $this->translator->trans('tSvg'),
                'data_class' => null,
                'mapped'=>false,
                'required'=>false,
                'attr' => [
                    'placeholder' => 'user_choose_file',
                ],
                'label_attr' => [
                    'data-browse' => $this->translator->trans('bt_Browse')
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/svg+xml',
                            'image/svg',
                            'application/xml'
                        ],
                        'mimeTypesMessage' => $this->translator->trans('tPlease upload a valid SVG'),
                    ])
                ]
            ])
            ->add('svgColor', ColorType::class, [
                'label' => $this->translator->trans('tColor'),
            ])
            ->add('category', TextType::class, [
                'label' => $this->translator->trans('tCategory'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Svg::class,
        ]);
    }
}
