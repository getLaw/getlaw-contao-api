<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  OnSaveDataEvent.php
 * @since       18.08.2020 - 18:24
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Events;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OnSaveDataEvent
 * @package Esit\Getlawclient\Classes\Events
 */
class OnSaveDataEvent extends Event
{


    /**
     * Name des Events
     */
    public const NAME = 'getlaw.on.save.data.event';


    /**
     * Name der Tabelle in der die Daten gespeichert werden.
     * @var string
     */
    protected $table = 'tl_content';


    /**
     * Names des Feldes in dem die Zeit des Abrufs der Daten gespeichert wird.
     * @var string
     */
    protected $timeField = 'savedon';


    /**
     * Name des Feldes in dem die Daten gespeichert werden.
     * @var string
     */
    protected $dataField = 'getlawdata';


    /**
     * Id des Contentelements.
     * @var int
     */
    protected $cteId = 0;


    /**
     * Daten der Schnittstelle
     * @var array
     */
    protected $data = [];


    /**
     * Json der Daten
     * @var string
     */
    protected $json = '';


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
    public function getTimeField(): string
    {
        return $this->timeField;
    }


    /**
     * @param string $timeField
     */
    public function setTimeField(string $timeField): void
    {
        $this->timeField = $timeField;
    }


    /**
     * @return string
     */
    public function getDataField(): string
    {
        return $this->dataField;
    }


    /**
     * @param string $dataField
     */
    public function setDataField(string $dataField): void
    {
        $this->dataField = $dataField;
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
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


    /**
     * @return string
     */
    public function getJson(): string
    {
        return $this->json;
    }


    /**
     * @param string $json
     */
    public function setJson(string $json): void
    {
        $this->json = $json;
    }
}
