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
use Esit\Getlawclient\Classes\Events\OnLoadDataEvent;
use Esit\Getlawclient\Classes\Events\OnSaveDataEvent;
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;
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
     * @var EventDispatcherInterface
     */
    protected $di;


    /**
     * @var LogHelper
     */
    protected $logger;


    /**
     * Generate the content element
     */
    protected function compile(): void
    {
        if ('BE' === TL_MODE) {
            $this->genBeOutput();
        } else {
            $this->di       = System::getContainer()->get('event_dispatcher');
            $this->logger   = System::getContainer()->get('esit_getlawclient.services.helper.log_helper');
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
        $content = $GLOBALS['TL_LANG']['MSC']['nodata'];

        if (null !== $this->getlawdata) {
            $content = $this->getContent($this->getlawdata);
        }

        if (empty($this->savedon) || $this->savedon < (\time() - (24 * 3600))) {
            $data = $this->loadData($this->getlawtextkey);

            if (false === $data['error'] && !empty($data['content'])) {
                $this->saveData((int)$this->id, $data);
                $content = $data['content'];
            }
        }

        $this->Template->content = $content;
    }


    /**
     * Ruft das Laden der Daten über die Schnittstelle auf.
     * @param  string $textkey
     * @return array
     */
    protected function loadData(string $textkey): array
    {
        $event = new OnLoadDataEvent();
        $event->setTextkey($textkey);
        $event->setHost('https://docgen.esit-testserver.de:44372/contentapi/'); #@todo Auslagern!!!

        $this->di->dispatch($event::NAME, $event);

        return $event->getData();
    }


    /**
     * Speichert die Daten in der Db.
     * @param int   $cteId
     * @param array $data
     */
    protected function saveData(int $cteId, array $data): void
    {
        $event = new OnSaveDataEvent();
        $event->setData($data);
        $event->setCteId($cteId);

        $this->di->dispatch($event::NAME, $event);
    }


    /**
     * Liest den gespeicherten Inhalt aus der Db aus.
     * @param  string $getlawData
     * @return string
     */
    protected function getContent(string $getlawData): string
    {
        #@todo in Alle decode-Konstrukte in einen Helper auslagern!!!
        try {
            $data = \json_decode($getlawData, true, 512, \JSON_THROW_ON_ERROR);

            if (!empty($data['content'])) {
                return $data['content'];
            }
        } catch (\JsonException $e) {
            $this->logger->addError($e->getMessage(), __METHOD__);
        }

        return '';
    }
}
