<?php
declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  ContentGetlawText.php
 * @since       18.08.2020 - 17:00
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Contao\Elements;

use Contao\System;
use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ContentGetlawText
 * @package Getlawclient\Classes\Contao\Elements
 */
class ContentGetlawText extends \ContentElement
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
    protected $getlawServer = '';


    /**
     * Name des Versionsheaders der getLaw-Api.
     * @var string
     */
    protected $getlawHeader = '';


    /**
     * Majorversion der API.
     * @var string
     */
    protected $apiVersion = '';


    /**
     * @var EventDispatcherInterface
     */
    protected $di;


    /**
     * Generate the content element
     */
    protected function compile(): void
    {
        if ('BE' === TL_MODE) {
            $this->genBeOutput();
        } else {
            $this->di           = System::getContainer()->get('event_dispatcher');
            $this->getlawServer = $GLOBALS['getLaw']['server_url'] ?: 'https://www.getlaw.de/api/texts/';
            $this->apiVersion   = $GLOBALS['getLaw']['api_header'] ?: 'X-getLaw-API-Version';
            $this->getlawHeader = $GLOBALS['getLaw']['api_version'] ?: '1';
            $this->genFeOutput();
        }
    }


    /**
     * Erzeugt die Ausgabe für das Backend.
     */
    protected function genBeOutput(): void
    {
        $this->strTemplate        = 'be_wildcard';
        $this->Template           = new \BackendTemplate($this->strTemplate);
        $this->Template->title    = $this->headline;
        $this->Template->wildcard = '### ContentGetlawText ###';
    }


    /**
     * Erzeugt die Ausgabe für das Frontend.
     */
    protected function genFeOutput(): void
    {
        $content = '';

        if (!empty($GLOBALS['TL_LANG']['MSC']['nodata'])) {
            $content = $GLOBALS['TL_LANG']['MSC']['nodata'];
        }

        $event = new OnHandleDataEvent();
        $event->setContent((string)$content);
        $event->setGetlawServer((string)$this->getlawServer);
        $event->setGetlawHeader($this->getlawHeader);
        $event->setApiVersion($this->apiVersion);
        $event->setTextkey((string)$this->getlawtextkey);
        $event->setDataStingFromDb((string)$this->getlawdata);
        $event->setSavedOn((int)$this->savedon);
        $event->setCteId((int)$this->id);
        $event->setDisableRenew((bool)$this->getlawdisableautorenew);
        $event->setManualRenew(false);

        $this->di->dispatch($event::NAME, $event);

        $this->Template->content = $event->getContent();
    }
}
