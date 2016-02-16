<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 16/02/16
 * Time: 13:46
 */

namespace Seta\CoreBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewRentalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'label.user',
                'class' => 'Seta\UserBundle\Entity\User',
                'choice_label' => 'displayName',
                'attr' => ['data-widget' => 'select2']
            ])
            ->add('zone', ChoiceType::class, [
                'label' => 'label.zone',
                'choices' => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'label.submit'
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'new_rental';
    }
}