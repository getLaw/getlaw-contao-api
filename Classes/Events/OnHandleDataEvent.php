<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  OnHandleDataEvent.php
 * @since       19.08.2020 - 16:22
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Events;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OnHandleDataEvent
 * @package Esit\Getlawclient\Classes\Events
 */
class OnHandleDataEvent extends Event
{


    /**
     * Name des Events
     */
    public const NAME = 'getlaw.on.handle.data.event';


    /**
     * Id des Inhaltselements
     * @var int
     */
    protected $cteId = 0;


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
     * Bei true wird der Text nicht automartisch neu vom Server geladen.
     * @var bool
     */
    protected $disableRenew = false;


    /**
     * Ist der Wert auf true gesetzt, hat der Uaer das Neuladen des Textes manuell angestoßen,
     * es wird kein Zeitintervall geprüft.
     * @var bool
     */
    protected $manualRenew = false;


    /**
     * Datum des letzten Datenabrufs.
     * @var int
     */
    protected $savedOn = 0;


    /**
     * Bereits in der Db gespeicherter Datenstring, falls vorhanden.
     * @var string
     */
    protected $dataStingFromDb = '';


    /**
     * Deserialisierte Daten aus der Db.
     * @var array
     */
    protected $dataFromDb = [];


    /**
     * Daten von der Schnittstelle, falls geladen.
     * @var array
     */
    protected $dataFromApi = [];


    /**
     * Text aus der Db oder von der Schnittstelle.
     * @var string
     */
    protected $content = '';


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
     * @return bool
     */
    public function getDisableRenew(): bool
    {
        return $this->disableRenew;
    }


    /**
     * @param bool $disableRenew
     */
    public function setDisableRenew(bool $disableRenew): void
    {
        $this->disableRenew = $disableRenew;
    }


    /**
     * @return bool
     */
    public function getManualRenew(): bool
    {
        return $this->manualRenew;
    }


    /**
     * @param bool $manualRenew
     */
    public function setManualRenew(bool $manualRenew): void
    {
        $this->manualRenew = $manualRenew;
    }


    /**
     * @return int
     */
    public function getSavedOn(): int
    {
        return $this->savedOn;
    }


    /**
     * @param int $savedOn
     */
    public function setSavedOn(int $savedOn): void
    {
        $this->savedOn = $savedOn;
    }


    /**
     * @return string
     */
    public function getDataStingFromDb(): string
    {
        return $this->dataStingFromDb;
    }


    /**
     * @param string $dataStingFromDb
     */
    public function setDataStingFromDb(string $dataStingFromDb): void
    {
        $this->dataStingFromDb = $dataStingFromDb;
    }


    /**
     * @return array
     */
    public function getDataFromDb(): array
    {
        return $this->dataFromDb;
    }


    /**
     * @param array $dataFromDb
     */
    public function setDataFromDb(array $dataFromDb): void
    {
        $this->dataFromDb = $dataFromDb;
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


    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }


    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
