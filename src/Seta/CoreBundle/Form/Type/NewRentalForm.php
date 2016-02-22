<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 16/02/16
 * Time: 13:46.
 */
namespace Seta\CoreBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

class NewRentalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'label.user',
                'class' => 'Seta\UserBundle\Entity\User',
                'choice_label' => 'displayName',
                'attr' => ['data-widget' => 'select2'],
                'required' => true,
                'constraints' => new Valid(),
                'placeholder' => 'Selecciona un usuario',
            ])
            ->add('zone', EntityType::class, [
                'label' => 'label.zone',
                'class' => 'Seta\LockerBundle\Entity\Zone',
                'expanded' => true,
                'required' => true,
                'constraints' => new Valid(),
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'new_rental';
    }
}
