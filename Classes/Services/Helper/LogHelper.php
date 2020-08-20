<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  LogHelper.php
 * @since       18.08.2020 - 18:14
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Services\Helper;

use Contao\CoreBundle\Monolog\ContaoContext;
use Monolog\Logger;

/**
 * Class LogHelper
 * @package Esit\Getlawclient\Classes\Services\Helper
 */
class LogHelper
{


    /**
     * @var Logger
     */
    protected $logger;


    /**
     * LogHelper constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Fügt dem Log eine Fehlermeldung hinzu.
     * @param string $msg
     * @param string $place
     */
    public function addError(string $msg, string $place): void
    {
        $this->logger->addError($msg, ['contao' => new ContaoContext($place, TL_ERROR)]);
    }
}
