<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\BsDatetimepickerBundle\Twig;


use Sgomez\BsDatetimepickerBundle\Templating\BsDatetimepickerHelper;

class BsDatetimepickerExtension extends \Twig_Extension
{
    /**
     * @var BsDatetimepickerHelper
     */
    private $helper;

    /**
     * BsDatetimepickerExtension constructor.
     */
    public function __construct(BsDatetimepickerHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getFunctions()
    {
        $options = [ 'is_safe' => [ 'html' ] ];

        return array(
            new \Twig_SimpleFunction('bsdatetimepicker_css_path', array($this, 'renderCssPath'), $options),
            new \Twig_SimpleFunction('bsdatetimepicker_js_path', array($this, 'renderJsPath'), $options),
            new \Twig_SimpleFunction('bsdatetimepicker_locale_path', array($this, 'renderLocalePath'), $options),
        );
    }

    public function renderCssPath($cssPath)
    {
        return $this->helper->renderCssPath($cssPath);
    }

    public function renderJsPath($jsPath)
    {
        return $this->helper->renderJsPath($jsPath);
    }

    public function renderLocalePath($basePath, $locale)
    {
        return $this->helper->renderLocalePath($basePath, $locale);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return $this->helper->getName();
    }
}