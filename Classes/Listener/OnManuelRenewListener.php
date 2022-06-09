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
use Esit\Getlawclient\Classes\Events\OnManuelRenewEvent;
use Esit\Getlawclient\Classes\Services\Helper\ContaoHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnManuelRenewListener
 * @package Esit\Getlawclient\Classes\Listener
 */
class OnManuelRenewListener
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
        $this->query        = $connection->createQueryBuilder();
        $this->contaoHelper = $ctoHelper;
    }


    /**
     * Lädt die Daten des CTE.
     * @param OnManuelRenewEvent $event
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadCte(OnManuelRenewEvent $event): void
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
     * @param OnManuelRenewEvent       $event
     * @param string                   $name
     * @param EventDispatcherInterface $di
     */
    public function loadText(OnManuelRenewEvent $event, string $name, EventDispatcherInterface $di): void
    {
        $cteId          = $event->getCteId();
        $textkey        = $event->getTextkey();
        $getlawServer   = $event->getGetlawServer();
        $header         = $event->getGetlawHeader();
        $version        = $event->getApiVersion();

        if (!empty($cteId) && !empty($textkey) && !empty($getlawServer) && !empty($header) && !empty($version)) {
            $handleData = new OnHandleDataEvent();
            $handleData->setGetlawServer($getlawServer);
            $handleData->setGetlawHeader($header);
            $handleData->setApiVersion($version);
            $handleData->setTextkey($textkey);
            $handleData->setCteId($cteId);
            $handleData->setManualRenew(true);

            $di->dispatch($handleData, $handleData::NAME);
            $event->setDataFromApi($handleData->getDataFromApi());
        }
    }


    /**
     * Erzeugt die Rückmeldung an den Nutzer.
     * @param OnManuelRenewEvent $event
     */
    public function generateMessage(OnManuelRenewEvent $event): void
    {
        $data   = $event->getDataFromApi();
        $lang   = $event->getLang();

        if (isset($data['error']) && false === $data['error']) {
            $msg = !empty($lang['apiSuccess']) ? $lang['apiSuccess'] : 'Lade des Text erfolgreich';
            $this->contaoHelper->addCornfirmation($msg);

            return;
        }

        $msg = !empty($lang['apiError']) ? $lang['apiError'] : 'Beim Laden des Textes ist ein Fehler aufgetreten';
        $this->contaoHelper->addError($msg);
    }


    /**
     * Leitet zurück zur Liste.
     * @param OnManuelRenewEvent $event
     */
    public function redirect(OnManuelRenewEvent $event): void
    {
        $pid = $event->getArticleId();

        if (!empty($pid)) {
            $url = "contao?do=article&table=tl_content&id=$pid";
            $this->contaoHelper->redirect($url);
        }
    }
}
