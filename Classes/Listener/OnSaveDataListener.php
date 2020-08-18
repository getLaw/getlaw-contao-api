<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  OnSaveDataListener.php
 * @since       18.08.2020 - 18:25
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Listener;

use Doctrine\DBAL\Connection;
use Esit\Getlawclient\Classes\Events\OnSaveDataEvent;
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
     * @var LogHelper
     */
    protected $logger;


    /**
     * OnSaveDataListener constructor.
     * @param Connection $connection
     * @param LogHelper  $logger
     */
    public function __construct(Connection $connection, LogHelper $logger)
    {
        $this->query    = $connection->createQueryBuilder();
        $this->logger   = $logger;
    }


    /**
     * Wandelt das Array in ein Json-String um.
     * @param OnSaveDataEvent $event
     */
    public function generateJson(OnSaveDataEvent $event): void
    {
        $data = $event->getData();

        try {
            if (false === $data['error']) {
                $json = \json_encode($data, \JSON_THROW_ON_ERROR);
                $event->setJson($json);
            }
        } catch (\Exception $e) {
            $this->logger->addError($e->getMessage(), __METHOD__);
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

        $this->query
            ->update($table)
            ->set($timeField, '?')
            ->set($dataField, '?')
            ->setParameters($dbValues)
            ->where("id = $cteId")
            ->execute();
    }
}
