<?php

/**
 * @package     getlawclient
 * @since       09.06.2022 - 13:39
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Services\Helper;

use Esit\Getlawclient\Classes\Events\OnHandleMessageEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MessageHelper
{
    /**
     * Länge des API-Keys
     */
    public const KEY_LENGTH = 128;


    /**
     * Regulärer Ausdruck für die Beschaffenheit des API-Keys.
     */
    public const KEY_RGXP = '|[a-fA-F\d]{128}|';


    /**
     * @var EventDispatcherInterface
     */
    protected $di;


    /**
     * @param EventDispatcherInterface $di
     */
    public function __construct(EventDispatcherInterface $di)
    {
        $this->di = $di;
    }


    /**
     * Erzeugt die Rückmeldung für den Nutzer.
     * @param array  $data
     * @param array  $lang
     * @param string $textkey
     * @param bool   $setMesaage
     * @return string
     */
    public function generateMessage(array $data, array $lang, string $textkey, bool $setMesaage = true): string
    {
        $event = new OnHandleMessageEvent();
        $event->setData($data);
        $event->setLang($lang);
        $event->setTextkey($textkey);
        $event->setSetMessage($setMesaage);

        $this->di->dispatch($event);

        return $event->getMessageText();
    }
}
