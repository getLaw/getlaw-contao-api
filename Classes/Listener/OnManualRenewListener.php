<?php

/**
 * @package     getlawclient
 * @filesource  OnManuelRenewListener.php
 * @since       07.09.2020 - 13:24
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Listener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Esit\Getlawclient\Classes\Events\OnManualRenewEvent;
use Esit\Getlawclient\Classes\Services\Helper\ContaoHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnManuelRenewListener
 * @package Esit\Getlawclient\Classes\Listener
 */
class OnManualRenewListener
{
    /**
     * @var QueryBuilder
     */
    protected $query;


    /**
     * @var ContaoHelper
     */
    protected $contaoHelper;


    /**
     * OnManuelRenewListener constructor.
     * @param Connection   $connection
     * @param ContaoHelper $ctoHelper
     */
    public function __construct(Connection $connection, ContaoHelper $ctoHelper)
    {
        $this->query            = $connection->createQueryBuilder();
        $this->contaoHelper     = $ctoHelper;
    }


    /**
     * Lädt die Daten des CTE.
     * @param OnManualRenewEvent $event
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadCte(OnManualRenewEvent $event): void
    {
        $id     = $event->getCteId();
        $table  = $event->getTable();

        if (!empty($id) && !empty($table)) {
            $result = $this->query->select('*')->from($table)->where("id = $id")->executeQuery();
            $data   = $result->fetchAssociative();

            if (!empty($data['getlawtextkey'])) {
                $event->setTextkey($data['getlawtextkey']);
            }

            if (!empty($data['pid'])) {
                $event->setArticleId((int)$data['pid']);
            }
        }
    }


    /**
     * Ruft das Laden des Textes auf.
     * @param OnManualRenewEvent       $event
     * @param string                   $name
     * @param EventDispatcherInterface $di
     */
    public function loadText(OnManualRenewEvent $event, string $name, EventDispatcherInterface $di): void
    {
        $cteId          = $event->getCteId();
        $textkey        = $event->getTextkey();
        $getlawServer   = $event->getGetlawServer();
        $header         = $event->getGetlawHeader();
        $version        = $event->getApiVersion();
        $lang           = $event->getLang();

        if (!empty($cteId) && !empty($textkey) && !empty($getlawServer) && !empty($header) && !empty($version)) {
            $handleData = new OnHandleDataEvent();
            $handleData->setGetlawServer($getlawServer);
            $handleData->setGetlawHeader($header);
            $handleData->setApiVersion($version);
            $handleData->setTextkey($textkey);
            $handleData->setCteId($cteId);
            $handleData->setManualRenew(true);
            $handleData->setLang($lang);

            $di->dispatch($handleData);
            $event->setDataFromApi($handleData->getDataFromApi());
        }
    }


    /**
     * Leitet zurück zur Liste.
     * @param OnManualRenewEvent $event
     */
    public function redirect(OnManualRenewEvent $event): void
    {
        $pid = $event->getArticleId();

        if (!empty($pid)) {
            $url = "contao?do=article&table=tl_content&id=$pid";
            $this->contaoHelper->redirect($url);
        }
    }
}
