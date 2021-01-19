<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
                'attr' => ['class' => 'uk-textarea', 'rows' => 8],
                'constraints' => [
                    new NotNull([
                        'message' => 'Please enter a message',
                    ]),
                ],
            ])
            ->add('reply', SubmitType::class, [
                'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top uk-margin-bottom']
            ])
            ->add('cancel', ButtonType::class, [
                'attr' => ['class' => 'uk-button uk-button-default uk-margin-top uk-margin-bottom']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
