<?php

/**
 * @package     getlawclient
 * @filesource  OnManuelRenewEvent.php
 * @since       07.09.2020 - 13:11
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Events;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OnManuelRenewEvent
 * @package Esit\Getlawclient\Classes\Events
 */
class OnManuelRenewEvent extends Event
{
    /**
     * Name des Events
     */
    public const NAME = 'getlaw.on.manual.renew.event';


    /**
     * Spracharray $GLOBALS['TL_LANG']['MSC']
     * @var array
     */
    protected $lang = [];


    /**
     * Id des Inhaltselements
     * @var int
     */
    protected $cteId = 0;


    /**
     * Id des Artikels, in dem sich das CTE befindet.
     * Wrid für die Weiterleitung zur Liste benötigt.
     * @var int
     */
    protected $articleId = 0;


    /**
     * Name der Tabelle in der die Daten des CTE gespeichert werden.
     * @var string
     */
    protected $table = '';


    /**
     * Url des getLaw-Servers
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
     * Textkey
     * @var string
     */
    protected $textkey = '';


    /**
     * Antwort der API
     * @var array
     */
    protected $dataFromApi = [];


    /**
     * @return array
     */
    public function getLang(): array
    {
        return $this->lang;
    }


    /**
     * @param array $lang
     */
    public function setLang(array $lang): void
    {
        $this->lang = $lang;
    }


    /**
     * @return int
     */
    public function getCteId(): int
    {
        return $this->cteId;
    }


    /**
     * @param int $cteId
     */
    public function setCteId(int $cteId): void
    {
        $this->cteId = $cteId;
    }


    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }


    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }


    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }


    /**
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }


    /**
     * @return string
     */
    public function getGetlawServer(): string
    {
        return $this->getlawServer;
    }


    /**
     * @param string $getlawServer
     */
    public function setGetlawServer(string $getlawServer): void
    {
        $this->getlawServer = $getlawServer;
    }


    /**
     * @return string
     */
    public function getGetlawHeader(): string
    {
        return $this->getlawHeader;
    }


    /**
     * @param string $getlawHeader
     */
    public function setGetlawHeader(string $getlawHeader): void
    {
        $this->getlawHeader = $getlawHeader;
    }


    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }


    /**
     * @param string $apiVersion
     */
    public function setApiVersion(string $apiVersion): void
    {
        $this->apiVersion = $apiVersion;
    }


    /**
     * @return string
     */
    public function getTextkey(): string
    {
        return $this->textkey;
    }


    /**
     * @param string $textkey
     */
    public function setTextkey(string $textkey): void
    {
        $this->textkey = $textkey;
    }


    /**
     * @return array
     */
    public function getDataFromApi(): array
    {
        return $this->dataFromApi;
    }


    /**
     * @param array $dataFromApi
     */
    public function setDataFromApi(array $dataFromApi): void
    {
        $this->dataFromApi = $dataFromApi;
    }
}
