<?php

/**
 * @package     getlawclient
 * @filesource  OnSaveDataListener.php
 * @since       18.08.2020 - 18:25
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Listener;

use Doctrine\DBAL\Connection;
use Esit\Getlawclient\Classes\Events\OnSaveDataEvent;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;

/**
 * Class OnSaveDataListener
 * @package Esit\Getlawclient\Classes\Listener
 */
class OnSaveDataListener
{
    /**
     * @var \Doctrine\DBAL\Query\QueryBuilder
     */
    protected $query;


    /**
     * @var JsonHelper
     */
    protected $jsonHelper;


    /**
     * OnSaveDataListener constructor.
     * @param Connection $connection
     * @param LogHelper  $logger
     * @param JsonHelper $jsonHelper
     */
    public function __construct(Connection $connection, JsonHelper $jsonHelper)
    {
        $this->query        = $connection->createQueryBuilder();
        $this->jsonHelper   = $jsonHelper;
    }


    /**
     * Wandelt das Array in ein Json-String um.
     * @param OnSaveDataEvent $event
     */
    public function generateJson(OnSaveDataEvent $event): void
    {
        $data = $event->getData();

        if (isset($data['error']) && false === $data['error']) {
            $json = $this->jsonHelper->encode($data);
            $event->setJson($json);
        }
    }


    /**
     * Speichert die Daten.
     * @param OnSaveDataEvent $event
     */
    public function saveData(OnSaveDataEvent $event): void
    {
        $json       = $event->getJson();
        $table      = $event->getTable();
        $timeField  = $event->getTimeField();
        $dataField  = $event->getDataField();
        $cteId      = $event->getCteId();
        $dbValues   = [\time(), $json];

        $this->query->update($table)
            ->set($timeField, '?')
            ->set($dataField, '?')
            ->setParameters($dbValues)
            ->where("id = $cteId")
            ->execute();
    }
}
