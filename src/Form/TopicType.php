<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'uk-input']
            ])
            ->add('add_topic', SubmitType::class, [
                'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top uk-margin-bottom']
            ])
            ->add('cancel', ButtonType::class, [
                'attr' => ['class' => 'uk-button uk-button-default uk-margin-top uk-margin-bottom']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
