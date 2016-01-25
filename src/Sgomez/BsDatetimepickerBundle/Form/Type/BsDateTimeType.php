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


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BsDateTimeType extends AbstractType
{
    /** @var string */
    private $basePath = 'bundles/sgomezbsdatetimepicker';

    /** @var string */
    private $cssPath = 'bundles/sgomezbsdatetimepicker/css/bootstrap-datetimepicker.min.css';

    /** @var string */
    private $jsPath = 'bundles/sgomezbsdatetimepicker/js/bootstrap-datetimepicker.min.js';

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @return string
     */
    public function getCssPath()
    {
        return $this->cssPath;
    }

    /**
     * @param string $cssPath
     */
    public function setCssPath($cssPath)
    {
        $this->cssPath = $cssPath;
    }

    /**
     * @return string
     */
    public function getJsPath()
    {
        return $this->jsPath;
    }

    /**
     * @param string $jsPath
     */
    public function setJsPath($jsPath)
    {
        $this->jsPath = $jsPath;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('base_path', $options['base_path']);
        $builder->setAttribute('css_path', $options['css_path']);
        $builder->setAttribute('js_path', $options['js_path']);
        $builder->setAttribute('config', $options['config']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['base_path'] = $form->getConfig()->getAttribute('base_path');
        $view->vars['css_path'] = $form->getConfig()->getAttribute('css_path');
        $view->vars['js_path'] = $form->getConfig()->getAttribute('js_path');

        $config = $form->getConfig()->getAttribute('config');
        $config['language'] = isset($config['language']) ? $config['language'] : \Locale::getDefault();
        $config['autoclose'] = isset($config['autoclose']) ? $config['autoclose'] : true;

        // Convert DateTimeInterface objects to date strings before passing to JavaScript.
        foreach ($config as $name => $value) {
            if ($value instanceof \DateTime || $value instanceof \DateTimeInterface) {
                if (!$value instanceof \DateTimeImmutable) {
                    $value = clone $value;
                }
                $config[$name] = $value->format('Y-m-d H:i:s');
            }
        }
        $view->vars = array_replace($view->vars, array(
            'config' => $config,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget'            => 'single_text',
            'base_path'         => $this->basePath,
            'css_path'          => $this->cssPath,
            'js_path'           => $this->jsPath,
            'date_format'       => 'dd/MM/yyyy H:i',
            'format'            =>  "yyyy-MM-dd HH:mm",
            'config'            => [],
        ]);

        $allowedTypes = [
            'base_path'         => 'string',
            'css_path'          => 'string',
            'js_path'           => 'string',
            'config'            => 'array',
        ];

        foreach ($allowedTypes as $option => $types) {
            $resolver->addAllowedTypes($option, $types);
        }
    }

    public function getParent()
    {
        return DateTimeType::class;
    }

    public function getBlockPrefix()
    {
        return 'bsdatetime';
    }


}