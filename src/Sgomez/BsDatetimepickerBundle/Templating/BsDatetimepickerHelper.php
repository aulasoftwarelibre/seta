<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sgomez\BsDatetimepickerBundle\Templating;

use Symfony\Component\Asset\Packages;
use Symfony\Component\Templating\Helper\Helper;

class BsDatetimepickerHelper extends Helper
{
    /**
     * @var Packages
     */
    private $packages;

    /**
     * BsDatetimepickerHelper constructor.
     */
    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    /**
     * Render de css path.
     *
     * @param $cssPath
     *
     * @return string
     */
    public function renderCssPath($cssPath)
    {
        return $this->packages->getUrl($cssPath);
    }

    /**
     * Render the js path.
     *
     * @param $jsPath
     *
     * @return string
     */
    public function renderJsPath($jsPath)
    {
        return $this->packages->getUrl($jsPath);
    }

    /**
     * Render the locale path.
     *
     * @param $basePath
     * @param $locale
     *
     * @return string
     */
    public function renderLocalePath($basePath, $locale)
    {
        $localePath = sprintf('%s/js/locales/bootstrap-datetimepicker.%s.js', $basePath, $locale);

        return $this->packages->getUrl($localePath);
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'sgomez_bsdatetimepicker';
    }
}
