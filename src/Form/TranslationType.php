<?php

namespace App\Form;

use App\Entity\Keytext;
use App\Entity\Language;
use App\Entity\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Contracts\Translation\TranslatorInterface;


class TranslationType extends AbstractType
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => $this->translator->trans('tText'),

            ])
            ->add('language', EntityType::class, [
                'label' => $this->translator->trans('tLanguage'),
                'class' => Language::class,
                'choice_label' => 'denomination',
            ])
            ->add('keytext', EntityType::class, [
                'label' => $this->translator->trans('tkeytext'),
                'class' => Keytext::class,
                'choice_label' => 'keytext',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Translation::class,
        ]);
    }
}
