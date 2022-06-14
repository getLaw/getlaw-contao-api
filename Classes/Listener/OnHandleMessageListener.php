<?php

/**
 * @package     getlawclient
 * @since       09.06.2022 - 15:25
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Listener;

use Esit\Getlawclient\Classes\Events\OnHandleMessageEvent;
use Esit\Getlawclient\Classes\Services\Helper\ContaoHelper;
use Esit\Getlawclient\Classes\Services\Helper\MessageHelper;

class OnHandleMessageListener
{
    /**
     * @var ContaoHelper
     */
    protected $contaoHelper;


    /**
     * OnManuelRenewListener constructor.
     * @param ContaoHelper $ctoHelper
     */
    public function __construct(ContaoHelper $ctoHelper)
    {
        $this->contaoHelper = $ctoHelper;
    }


    /**
     * Verarbeitet den Fehler, wenn der Textkey leer ist.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function handleKeyEmpty(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();

        if ('' === $event->getTextkey()) {
            $msg = 'Es wurde noch kein API-Schlüssel eingetragen.';

            if (!empty($lang['apiErrorNoKey'])) {
                $msg = $lang['apiErrorNoKey'];
            }

            $event->setMessageText($msg);
        }
    }


    /**
     * Verarbeitet den Fehler, wenn der Textkey zu kurz ist.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function handleKeyTooShort(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();

        if (MessageHelper::KEY_LENGTH > \strlen($event->getTextkey())) {
            $msg = 'Der API-Schlüssel ist zu kurz.';

            if (!empty($lang['apiErrorTooShort'])) {
                $msg = $lang['apiErrorTooShort'];
            }

            $event->setMessageText($msg);
        }
    }


    /**
     * Verarbeitet den Fehler, wenn der Textkey zu lang ist.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function handleKeyTooLong(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();

        if (MessageHelper::KEY_LENGTH < \strlen($event->getTextkey())) {
            $msg = 'Der API-Schlüssel ist zu lang.';

            if (!empty($lang['apiErrorTooLong'])) {
                $msg = $lang['apiErrorTooLong'];
            }

            $event->setMessageText($msg);
        }
    }


    /**
     * Verarbeitet den Fehler, wenn der Textkey ungültige Zeichen enthält.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function handleWrongChar(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();

        if (0 === \preg_match(MessageHelper::KEY_RGXP, $event->getTextkey())) {
            $msg = 'Der API-Schlüssel enthält ungültige Zeichen.';

            if (!empty($lang['apiErrorWorngChar'])) {
                $msg = $lang['apiErrorWorngChar'];
            }

            $event->setMessageText($msg);
        }
    }


    /**
     * Verarbeitet den Fehler, wenn der Textkey falsch ist.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function handleWrongKey(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();
        $data = $event->getData();

        if (!empty($data['errorcode']) && 404 === $data['errorcode']) {
            $msg = 'Der API-Schlüssel ist falsch.';

            if (!empty($lang['apiErrorNotFound'])) {
                $msg = $lang['apiErrorNotFound'];
            }

            $event->setMessageText($msg);
        }
    }


    /**
     * Verarbeitet den Fehler, wenn der Textkey falsch ist.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function handleApiError(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();
        $data = $event->getData();

        if (empty($data) || (isset($data['error']) && true === $data['error'])) {
            $msg = 'Beim Laden des Textes ist ein Fehler aufgetreten.';

            if (!empty($lang['apiError'])) {
                $msg = $lang['apiError'];
            }

            $event->setMessageText($msg);
        }
    }


    /**
     * Setzt die Erfolgsmeldung, wenn kein Fehler gefunden wurde.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function setSuccessMessage(OnHandleMessageEvent $event): void
    {
        $lang = $event->getLang();

        if ('' === $event->getMessageText() && true === $event->getSetMessage()) {
            $msg = 'Laden des Text war erfolgreich.';

            if (!empty($lang['apiSuccess'])) {
                $msg = $lang['apiSuccess'];
            }

            $this->contaoHelper->addCornfirmation($msg);
        }
    }


    /**
     * Setzt die Meldung für den Benutzer.
     * @param OnHandleMessageEvent $event
     * @return void
     */
    public function setErrorMessage(OnHandleMessageEvent $event): void
    {
        if ('' !== $event->getMessageText() && true === $event->getSetMessage()) {
            $this->contaoHelper->addError($event->getMessageText());
        }
    }
}
