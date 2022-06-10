<?php

/**
 * @package     getlawclient
 * @since       10.06.2022 - 09:57
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Tests\Listener;

use Esit\Getlawclient\Classes\Events\OnHandleMessageEvent;
use Esit\Getlawclient\Classes\Listener\OnHandleMessageListener;
use Esit\Getlawclient\Classes\Services\Helper\ContaoHelper;
use Esit\Getlawclient\EsitTestCase;

class OnHandleMessageListenerTest extends EsitTestCase
{


    /**
     * @var ContaoHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $ctoHelper;


    /**
     * @var OnHandleMessageEvent
     */
    protected $event;


    /**
     * @var OnHandleMessageListener
     */
    protected $listner;


    protected function setUp(): void
    {
        $this->ctoHelper    = $this->getMockBuilder(ContaoHelper::class)->disableOriginalConstructor()->getMock();
        $this->event        = $this->getMockBuilder(OnHandleMessageEvent::class)->getMock();
        $this->listner      = new OnHandleMessageListener($this->ctoHelper);
    }


    public function testHandleKeyEmptyDoNothingIfKeyIsNotEmtpy(): void
    {
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn('1234567890abcdef');
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleKeyEmpty($this->event);
    }


    public function testHandleKeyEmptySetDefaultErrorMessageIfLanguageArrayIsEmpty(): void
    {
        $msg = 'Es wurde noch kein API-Schlüssel eingetragen.';
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn('');
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleKeyEmpty($this->event);
    }


    public function testHandleKeyEmptySetErrorMessageIfLanguageArrayIsSet(): void
    {
        $msg = 'Es wurde noch kein API-Schlüssel eingetragen.';
        $msg.= ' Message from Array';
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiErrorNoKey' => $msg]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn('');
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleKeyEmpty($this->event);
    }


    public function testHandleKeyTooShortDoNothingIfKeyIsLongEnough(): void
    {
        $textkey = \str_repeat('abcd1234', 16);
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleKeyTooShort($this->event);
    }


    public function testHandleKeyTooShortSetDefaultErrorMessageIfLanguageArrayIsEmpty(): void
    {
        $msg        = 'Der API-Schlüssel ist zu kurz.';
        $textkey    = \str_repeat('abcd1234', 15);
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleKeyTooShort($this->event);
    }


    public function testHandleKeyTooShortSetErrorMessageIfLanguageArrayIsSet(): void
    {
        $msg        = 'Der API-Schlüssel ist zu kurz.';
        $msg       .= ' Message from Array';
        $textkey    = \str_repeat('abcd1234', 15);
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiErrorTooShort' => $msg]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleKeyTooShort($this->event);
    }


    public function testHandleKeyTooLongDoNothingIfKeyIsLongEnough(): void
    {
        $textkey = \str_repeat('abcd1234', 16);
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleKeyTooLong($this->event);
    }


    public function testHandleKeyTooLongSetDefaultErrorMessageIfLanguageArrayIsEmpty(): void
    {
        $msg        = 'Der API-Schlüssel ist zu lang.';
        $textkey    = \str_repeat('abcd1234', 17);
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleKeyTooLong($this->event);
    }


    public function testHandleKeyTooLongSetErrorMessageIfLanguageArrayIsSet(): void
    {
        $msg        = 'Der API-Schlüssel ist zu lang.';
        $msg       .= ' Message from Array';
        $textkey    = \str_repeat('abcd1234', 17);
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiErrorTooLong' => $msg]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleKeyTooLong($this->event);
    }


    public function testHandleWrongCharDoNothingIfKeyHasTheRightChars(): void
    {
        $textkey = \str_repeat('abcd1234', 16);
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleWrongChar($this->event);
    }


    public function testHandleWrongCharSetDefaultErrorMessageIfLanguageArrayIsEmpty(): void
    {
        $msg        = 'Der API-Schlüssel enthält ungültige Zeichen.';
        $textkey    = \str_repeat('abcd1234', 16);
        $textkey    = \str_replace('c', 'z', $textkey);
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleWrongChar($this->event);
    }


    public function testHandleWrongCharSetErrorMessageIfLanguageArrayIsSet(): void
    {
        $msg        = 'Der API-Schlüssel enthält ungültige Zeichen.';
        $msg       .= ' Message from Array';
        $textkey    = \str_repeat('abcd1234', 16);
        $textkey    = \str_replace('c', 'z', $textkey);
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiErrorWorngChar' => $msg]);
        $this->event->expects(self::once())->method('getTextkey')->willReturn($textkey);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleWrongChar($this->event);
    }


    public function testHandleWrongKeyDoNothingIfErrorcodeIstEmtpy(): void
    {
        $data = [];
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleWrongKey($this->event);
    }


    public function testHandleWrongKeyDoNothingIfErrorcodeIsNot404(): void
    {
        $data = ['errorcode' => 400];
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleWrongKey($this->event);
    }


    public function testHandleWrongKeyetDefaultErrorMessageIfErrorcodeIs404(): void
    {
        $data   = ['errorcode' => 404];
        $msg    = 'Der API-Schlüssel ist falsch.';
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleWrongKey($this->event);
    }


    public function testHandleWrongKeySetErrorMessageIfLanguageArrayIsSet(): void
    {
        $data   = ['errorcode' => 404];
        $msg    = 'Der API-Schlüssel ist falsch.';
        $msg   .= ' Message from Array';
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiErrorNotFound' => $msg]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleWrongKey($this->event);
    }


    public function testHandleApiErrorDoNothingIfErrorIsFalse(): void
    {
        $data = ['error' => false];
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleApiError($this->event);
    }


    public function testHandleApiErrorDoNothingIfErrorIsNotSet(): void
    {
        $data = ['error_not' => false];
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::never())->method('setMessageText');
        $this->listner->handleApiError($this->event);
    }


    public function testHandleApiErrorSetDefaultMessageIfErrorIsNotFalse(): void
    {
        $data   = ['error' => true];
        $msg    = 'Beim Laden des Textes ist ein Fehler aufgetreten.';
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleApiError($this->event);
    }


    public function testHandleApiErrorSetDefaultMessageIfDataIsEmpty(): void
    {
        $data   = [];
        $msg    = 'Beim Laden des Textes ist ein Fehler aufgetreten.';
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleApiError($this->event);
    }


    public function testHandleApiErrorSetMessageIfDataIsEmptyAndMessageIsSet(): void
    {
        $data   = [];
        $msg    = 'Beim Laden des Textes ist ein Fehler aufgetreten.';
        $msg   .= ' Message from Array';
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiError' => $msg]);
        $this->event->expects(self::once())->method('getData')->willReturn($data);
        $this->event->expects(self::once())->method('setMessageText')->with($msg);
        $this->listner->handleApiError($this->event);
    }


    public function testSetSuccessMessageSetDefaultMessageIfNoErrorIsSetAndSetMessageIsTrue(): void
    {
        $msg = 'Lade des Text erfolgreich.';
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getSetMessage')->willReturn(true);
        $this->event->expects(self::once())->method('getMessageText')->willReturn('');
        $this->ctoHelper->expects(self::once())->method('addCornfirmation')->with($msg);
        $this->listner->setSuccessMessage($this->event);
    }


    public function testSetSuccessMessageSetMessageIfNoErrorIsSetAndSetMessageIsTrue(): void
    {
        $msg = 'Lade des Text erfolgreich.';
        $msg   .= ' Message from Array';
        $this->event->expects(self::once())->method('getLang')->willReturn(['apiSuccess' => $msg]);
        $this->event->expects(self::once())->method('getSetMessage')->willReturn(true);
        $this->event->expects(self::once())->method('getMessageText')->willReturn('');
        $this->ctoHelper->expects(self::once())->method('addCornfirmation')->with($msg);
        $this->listner->setSuccessMessage($this->event);
    }


    public function testSetSuccessMessageDoNothingIfAnErrorIsSetAndSetMessageIsTrue(): void
    {
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::never())->method('getSetMessage');
        $this->event->expects(self::once())->method('getMessageText')->willReturn('Error');
        $this->ctoHelper->expects(self::never())->method('addCornfirmation');
        $this->listner->setSuccessMessage($this->event);
    }


    public function testSetSuccessMessageDoNothingIfNoErrorIsSetANdSetMessageIsFalse(): void
    {
        $this->event->expects(self::once())->method('getLang')->willReturn([]);
        $this->event->expects(self::once())->method('getSetMessage')->willReturn(false);
        $this->event->expects(self::once())->method('getMessageText')->willReturn('');
        $this->ctoHelper->expects(self::never())->method('addCornfirmation');
        $this->listner->setSuccessMessage($this->event);
    }


    public function testSetErrorMessageDoNothingIfMessageIsEmpty(): void
    {
        $this->event->expects(self::once())->method('getMessageText')->willReturn('');
        $this->event->expects(self::never())->method('getSetMessage');
        $this->ctoHelper->expects(self::never())->method('addError');
        $this->listner->setErrorMessage($this->event);
    }


    public function testSetErrorMessageDoNothingIfSetMessageIsFalse(): void
    {
        $this->event->expects(self::once())->method('getMessageText')->willReturn('Error');
        $this->event->expects(self::once())->method('getSetMessage')->willReturn(false);
        $this->ctoHelper->expects(self::never())->method('addError');
        $this->listner->setErrorMessage($this->event);
    }


    public function testSetErrorMessageSetMessageIfSetMessageIsTrueAndMessageIsSet(): void
    {
        $msg = 'Error';
        $this->event->expects(self::exactly(2))->method('getMessageText')->willReturn($msg);
        $this->event->expects(self::once())->method('getSetMessage')->willReturn(true);
        $this->ctoHelper->expects(self::once())->method('addError')->with($msg);
        $this->listner->setErrorMessage($this->event);
    }
}
