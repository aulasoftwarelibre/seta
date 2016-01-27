<?php
/*
 * This file is part of the SgomezBsDatetimepickerBundle.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sgomez\BsDatetimepickerBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BsDateType extends BsDateTimeType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $config = $view->vars['config'];
        $config['minView'] = 2;
        $config['format'] = 'yyyy-mm-dd';

        $view->vars = array_replace($view->vars, [
            'config' => $config,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'date_format' => 'dd/MM/yyyy',
            'format' => 'yyyy-MM-dd',
        ]);
    }

    public function getParent()
    {
        return DateType::class;
    }

    public function getBlockPrefix()
    {
        return 'bsdate';
    }
}
