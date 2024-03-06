<?php

/**
 * @package     getlawclient
 * @filesource  LogHelper.php
 * @since       18.08.2020 - 18:14
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Services\Helper;

use Contao\CoreBundle\Monolog\ContaoContext;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
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
        $this->logger->error($msg, ['contao' => new ContaoContext($place, 'ERROR')]);
    }
}
