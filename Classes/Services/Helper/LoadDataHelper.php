<?php

/**
 * @package     getlawclient
 * @since       10.08.2021 - 17:39
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Services\Helper;

use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadDataHelper
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_getlaw_text';


    /**
     * Url der getLaw-Schnittstelle
     * @var string
     */
    protected $getlawServer = 'https://www.getlaw.de/api/texts/';


    /**
     * Name des Versionsheaders der getLaw-Api.
     * @var string
     */
    protected $getlawHeader = 'X-getLaw-API-Version';


    /**
     * Majorversion der API.
     * @var string
     */
    protected $apiVersion = '1';


    /**
     * @var EventDispatcherInterface
     */
    protected $di;


    /**
     * Generate the content element
     * @param EventDispatcherInterface $di
     */
    public function __construct(EventDispatcherInterface $di)
    {
        $this->di = $di;

        if (!empty($GLOBALS['getLaw']['server_url'])) {
            $this->getlawServer = $GLOBALS['getLaw']['server_url'];
        }

        if (!empty($GLOBALS['getLaw']['api_header'])) {
            $this->getlawHeader = $GLOBALS['getLaw']['api_header'];
        }

        if (!empty($GLOBALS['getLaw']['api_version'])) {
            $this->apiVersion = $GLOBALS['getLaw']['api_version'];
        }
    }


    /**
     * Ruft das Laden des Textes vom getLaw-Server auf.
     * @param  string $getlawtextkey
     * @param  int    $id
     * @param  string $getlawdata
     * @param  int    $savedon
     * @param  bool   $getlawdisableautorenew
     * @return string
     */
    public function loadData(
        string $getlawtextkey,
        int $id,
        string $getlawdata,
        int $savedon,
        bool $getlawdisableautorenew = false
    ): string {
        $content = '';

        if (!empty($GLOBALS['TL_LANG']['MSC']['nodata'])) {
            $content = $GLOBALS['TL_LANG']['MSC']['nodata'];
        }

        $event = new OnHandleDataEvent();
        $event->setContent((string)$content);
        $event->setGetlawServer((string)$this->getlawServer);
        $event->setGetlawHeader($this->getlawHeader);
        $event->setApiVersion($this->apiVersion);
        $event->setTextkey($getlawtextkey);
        $event->setDataStingFromDb($getlawdata);
        $event->setSavedOn($savedon);
        $event->setCteId($id);
        $event->setDisableRenew($getlawdisableautorenew);
        $event->setManualRenew(false);

        $this->di->dispatch($event);

        return $event->getContent();
    }
}
