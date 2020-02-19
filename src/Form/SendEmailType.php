<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SendEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'label.email.subject',
            ])
            ->add('to', ChoiceType::class, [
                'label' => 'label.email.to',
                'placeholder' => 'placeholder.email.to',
                'choices' => [
                    'choice.email.rental' => 'rental',
                    'choice.email.all' => 'all',
                ],
            ])
            ->add('body', TextareaType::class, [
                'label' => 'label.email.body',
                'attr' => ['rows' => '20']
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'send_email';
    }
}
