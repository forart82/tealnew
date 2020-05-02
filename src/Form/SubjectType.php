<?php

namespace App\Form;

use App\Entity\Svg;
use App\Entity\Language;
use App\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextareaType::class, [
                'label' => $this->translator->trans('tQuestion'),
            ])
            ->add('answerOne', TextareaType::class, [
                'label' => $this->translator->trans('tAnswere 01'),
            ])
            ->add('answerTwo', TextareaType::class, [
                'label' => $this->translator->trans('tAnswere 02'),
            ])
            ->add('answerThree', TextareaType::class, [
                'label' => $this->translator->trans('tAnswere 03'),
            ])
            ->add('answerFour', TextareaType::class, [
                'label' => $this->translator->trans('tAnswere 04'),
            ])
            ->add('answerFive', TextareaType::class, [
                'label' => $this->translator->trans('tAnswere 05'),
            ])
            ->add('position', NumberType::class,[
                'label' => $this->translator->trans('tPosition'),
            ])
            ->add('userNotation', NumberType::class,[
                'label' => $this->translator->trans('tUserNotation'),
            ])
            ->add('title', TextType::class,[
                'label' => $this->translator->trans('ttitle'),
            ])
            ->add('isRespond', ChoiceType::class, [
                'label' => $this->translator->trans('tIsRespond'),
                'choices' => [
                    $this->translator->trans('tYes') => true,
                    $this->translator->trans('tNo') => false,
                ]
            ])
            ->add('language', EntityType::class, [
                'label' => $this->translator->trans('tLanguage'),
                'class' => Language::class,
                'choice_label' => 'denomination',
            ])
            ->add('svg', EntityType::class, [
                'label' => $this->translator->trans('Svg'),
                'class' => Svg::class,
                'choice_label' => 'svg',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
