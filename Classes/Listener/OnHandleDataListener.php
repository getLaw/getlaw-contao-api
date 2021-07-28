<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  OnHandleDataListener.php
 * @since       19.08.2020 - 16:27
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Listener;

use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Esit\Getlawclient\Classes\Events\OnLoadDataEvent;
use Esit\Getlawclient\Classes\Events\OnSaveDataEvent;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnHandleDataListener
 * @package Esit\Getlawclient\Classes\Listener
 */
class OnHandleDataListener
{


    /**
     * @var JsonHelper
     */
    protected $jsonHelper;


    /**
     * OnHandleDataListener constructor.
     * @param JsonHelper $jsonHelper
     */
    public function __construct(JsonHelper $jsonHelper)
    {
        $this->jsonHelper = $jsonHelper;
    }


    /**
     * Verarbeitet die Daten aus der Db.
     * @param OnHandleDataEvent $event
     */
    public function handleDbDataString(OnHandleDataEvent $event): void
    {
        $dbDataString = $event->getDataStingFromDb();

        if (!empty($dbDataString)) {
            $data = $this->jsonHelper->decode($dbDataString);
            $event->setDataFromDb($data);

            if (!empty($data['content'])) {
                $event->setContent($data['content']);
            }
        }
    }


    /**
     * Lädt die Daten über die Schnittstelle, falls nötig.
     * @param OnHandleDataEvent        $event
     * @param string                   $name
     * @param EventDispatcherInterface $di
     */
    public function loadData(OnHandleDataEvent $event, string $name, EventDispatcherInterface $di): void
    {
        $savedon        = $event->getSavedOn();
        $getlawServer   = $event->getGetlawServer();
        $textkey        = $event->getTextkey();
        $header         = $event->getGetlawHeader();
        $version        = $event->getApiVersion();
        $disable        = $event->getDisableRenew();
        $manual         = $event->getManualRenew();

        if (true === $manual || (false === $disable && $savedon < (\time() - (24 * 3600)))) {
            $loadEvent = new OnLoadDataEvent();
            $loadEvent->setTextkey($textkey);
            $loadEvent->setHost($getlawServer);
            $loadEvent->setGetlawHeader($header);
            $loadEvent->setApiVersion($version);

            $di->dispatch($loadEvent, $loadEvent::NAME);

            $event->setDataFromApi($loadEvent->getData());
        }
    }


    /**
     * Speichert die neuen Daten, falls sie geladen wurden.
     * @param OnHandleDataEvent        $event
     * @param string                   $name
     * @param EventDispatcherInterface $di
     */
    public function saveData(OnHandleDataEvent $event, string $name, EventDispatcherInterface $di): void
    {
        $data       = $event->getDataFromApi();
        $cteId      = $event->getCteId();

        if (isset($data['error']) && false === $data['error'] && !empty($data['content']) && !empty($cteId)) {
            $saveEvent = new OnSaveDataEvent();
            $saveEvent->setData($data);
            $saveEvent->setCteId($cteId);

            $di->dispatch($saveEvent, $saveEvent::NAME);
            $event->setContent($data['content']);
        }
    }
}
