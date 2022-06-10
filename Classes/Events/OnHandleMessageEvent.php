<?php

/**
 * @package     getlawclient
 * @since       09.06.2022 - 15:16
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Events;

use Symfony\Contracts\EventDispatcher\Event;

class OnHandleMessageEvent extends Event
{
    /**
     * Vom Server geladene Daten.
     * @var array
     */
    private $data = [];


    /**
     * API-Key zum Laden der Texte.
     * @var string
     */
    private $textkey = '';


    /**
     * Spracharray mit den Fehlermeldungen.
     * @var array
     */
    private $lang = [];


    /**
     * Soll eine Meldung in Contao erzeugt werden?
     * (Beim Auswerte des regulären Ausdrucks im DCA ist das nicht der Fall.)
     * @var bool
     */
    private $setMessage = true;


    /**
     * Meldung an den Benutzer.
     * @var string
     */
    private $messageText = '';


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
     * @return bool
     */
    public function getSetMessage(): bool
    {
        return $this->setMessage;
    }


    /**
     * @param bool $setMessage
     */
    public function setSetMessage(bool $setMessage): void
    {
        $this->setMessage = $setMessage;
    }


    /**
     * @return string
     */
    public function getMessageText(): string
    {
        return $this->messageText;
    }


    /**
     * @param string $messageText
     */
    public function setMessageText(string $messageText): void
    {
        $this->messageText = $messageText;
    }
}
