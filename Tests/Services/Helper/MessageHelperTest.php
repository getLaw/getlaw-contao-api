<?php

/**
 * @package     getlawclient
 * @since       10.06.2022 - 09:41
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Tests\Services\Helper;

use Esit\Getlawclient\Classes\Events\OnHandleMessageEvent;
use Esit\Getlawclient\Classes\Services\Helper\MessageHelper;
use Esit\Getlawclient\EsitTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MessageHelperTest extends EsitTestCase
{


    public function testGenerateMessageKeyLengthIs128(): void
    {
        self::assertSame(128, MessageHelper::KEY_LENGTH);
    }


    public function testGenerateMessageRgxpIsOkay(): void
    {
        self::assertSame('|[a-fA-F\d]{128}|', MessageHelper::KEY_RGXP);
    }


    public function testGenerateMessageDispatchEvent(): void
    {
        $data           = ['test' => 'Test 001'];
        $lang           = ['apiError' => 'Es ist ein Fehler aufgetreten'];
        $textkey        = 'e508785b0a6ff9da6eed74e0fe0a12881e0f4610d7db558d2dcca3ce362815bd2b6a68ffe410fe22d8e3c1b';
        $textkey       .= '05980f1e5cfe338f69aeebf4630353e4a78e212e9';
        $setMessage     = false;
        $content        = 'Lorem ipsum dolor sit amet, conseter adipising elit. Aenan ligula dolor.';
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(function(OnHandleMessageEvent $event) {
                    $textkey        = 'e508785b0a6ff9da6eed74e0fe0a12881e0f4610d7db558d2dcca3ce362815bd2b6a68';
                    $textkey       .= 'ffe410fe22d8e3c1b05980f1e5cfe338f69aeebf4630353e4a78e212e9';
                    self::assertEquals(['test' => 'Test 001'], $event->getData());
                    self::assertEquals(['apiError' => 'Es ist ein Fehler aufgetreten'], $event->getLang());
                    self::assertEquals($textkey, $event->getTextkey());
                    self::assertEquals(false, $event->getSetMessage());
                    $event->setMessageText('Lorem ipsum dolor sit amet, conseter adipising elit. Aenan ligula dolor.');

                    return true;
                })
            );

        $helper = new MessageHelper($eventDispatcher);
        $rtn    = $helper->generateMessage($data, $lang, $textkey, $setMessage);

        self::assertSame($content, $rtn);
    }
}
