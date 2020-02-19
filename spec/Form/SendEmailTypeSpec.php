<?php

namespace spec\Seta\CoreBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SendEmailTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('App\Form\SendEmailType');
    }
    
    function it_is_an_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_has_some_fields(FormBuilderInterface $builder)
    {
        $builder->add('subject', TextType::class, Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('to', ChoiceType::class, Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('body', TextareaType::class, Argument::cetera())->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, []);
    }

    function it_has_a_block_prefix()
    {
        $this->getBlockPrefix()->shouldReturn('send_email');
    }
}
