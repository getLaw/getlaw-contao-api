<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  Plugin.php
 * @version     1.0.0
 * @since       18.08.2020 - 16:13
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Contao\Manager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Class Plugin
 * @package Esit\Getlawclient\Classes\Contao\Manager
 */
class Plugin implements BundlePluginInterface
{


    /**
     * @param  ParserInterface                                             $parser
     * @return array|\Contao\ManagerPlugin\Bundle\Config\ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(\Esit\Getlawclient\EsitGetlawclientBundle::class)
                ->setLoadAfter([\Contao\CoreBundle\ContaoCoreBundle::class])
        ];
    }
}
