<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  OnLoadDataEvent.php
 * @since       18.08.2020 - 17:35
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class OnLoadDataEvent
 * @package Esit\Getlawclient\Classes\Events
 */
class OnLoadDataEvent extends Event
{


    /**
     * Name des Events
     */
    public const NAME = 'getlaw.on.load.data.event';


    /**
     * Url der API
     * @var string
     */
    protected $host = '';


    /**
     * Textkey
     * @var string
     */
    protected $textkey = '';


    /**
     * Url des Textes
     * @var string
     */
    protected $url = '';


    /**
     * Daten der Schnittstelle
     * @var array
     */
    protected $data = [];


    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }


    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
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
}
