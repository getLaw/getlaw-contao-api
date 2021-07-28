<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  OnHandleDataListenerTest.php
 * @since       20.08.2020 - 12:11
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Tests\Listener;

use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Esit\Getlawclient\Classes\Events\OnLoadDataEvent;
use Esit\Getlawclient\Classes\Events\OnSaveDataEvent;
use Esit\Getlawclient\Classes\Listener\OnHandleDataListener;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Esit\Getlawclient\EsitTestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnHandleDataListenerTest
 * @package Esit\Getlawclient\Tests\Listener
 */
class OnHandleDataListenerTest extends EsitTestCase
{


    protected $jsonHelper;


    protected function setUp(): void
    {
        $this->jsonHelper   = $this->getMockBuilder(JsonHelper::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['decode'])
                                   ->getMock();
    }


    public function testHandleDbDataStringDoNothingIfDbStringIsEmpty(): void
    {
        $this->jsonHelper->expects(self::never())->method('decode');
        $event      = new OnHandleDataEvent();
        $listener   = new OnHandleDataListener($this->jsonHelper);
        $listener->handleDbDataString($event);
    }


    public function testHandleDbDataStringCallJsonHelperIfDbDataStringIsNotEmpty(): void
    {
        $this->jsonHelper->expects(self::once())->method('decode')->with('dbDataString')->willReturn(['testData']);
        $event      = new OnHandleDataEvent();
        $event->setDataStingFromDb('dbDataString');
        $listener   = new OnHandleDataListener($this->jsonHelper);
        $listener->handleDbDataString($event);
        self::assertSame(['testData'], $event->getDataFromDb());
        self::assertEmpty($event->getContent());
    }


    public function testHandleDbDataStringSetContentIfContentIsNotEmpty(): void
    {
        $data       = ['testData', 'content' => 'TestContent'];
        $this->jsonHelper->expects(self::once())->method('decode')->with('dbDataString')->willReturn($data);
        $event      = new OnHandleDataEvent();
        $event->setDataStingFromDb('dbDataString');
        $listener   = new OnHandleDataListener($this->jsonHelper);
        $listener->handleDbDataString($event);
        self::assertSame($data, $event->getDataFromDb());
        self::assertSame('TestContent', $event->getContent());
    }


    public function testLoadDataDoNothingIfSaveOnIsNewerThenOneDay(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setSavedOn(\time());
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->loadData($event, '', $eventDispatcher);
    }


    public function testLoadDataDoNothingIfAutoRenewIsDisabled(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setDisableRenew(true);
        $event->setSavedOn(\time()- (48 * 3600));
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->loadData($event, '', $eventDispatcher);
    }


    public function testLoadDataCallsEventDispatcherIfSaveonIsNotEmpty(): void
    {
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function(OnLoadDataEvent $event) {
                    self::assertEmpty($event->getTextkey());
                    self::assertEmpty($event->getHost());
                    $event->setData(['testData']);

                    return true;
                }),
                self::equalTo(OnLoadDataEvent::NAME)
            );

        $event              = new OnHandleDataEvent();
        $event->setSavedOn(\time()- (48 * 3600));
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->loadData($event, '', $eventDispatcher);
        self::assertSame(['testData'], $event->getDataFromApi());
    }


    public function testLoadDataCallsEventDispatcherIfManualRenewIsTrue(): void
    {
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function(OnLoadDataEvent $event) {
                    self::assertEmpty($event->getTextkey());
                    self::assertEmpty($event->getHost());
                    $event->setData(['testData']);

                    return true;
                }),
                self::equalTo(OnLoadDataEvent::NAME)
            );

        $event              = new OnHandleDataEvent();
        $event->setManualRenew(true);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->loadData($event, '', $eventDispatcher);
        self::assertSame(['testData'], $event->getDataFromApi());
    }


    public function testLoadDataSetDataInEventIfSaveonIsNotEmpty(): void
    {
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function(OnLoadDataEvent $event) {
                    self::assertSame('textkey', $event->getTextkey());
                    self::assertSame('https://example.org/', $event->getHost());
                    self::assertSame('X-test-Header', $event->getGetlawHeader());
                    self::assertSame('12', $event->getApiVersion());
                    $event->setData(['testData']);

                    return true;
                }),
                self::equalTo(OnLoadDataEvent::NAME)
            );

        $event              = new OnHandleDataEvent();
        $event->setSavedOn(\time()- (48 * 3600));
        $event->setTextkey('textkey');
        $event->setGetlawServer('https://example.org/');
        $event->setGetlawHeader('X-test-Header');
        $event->setApiVersion('12');
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->loadData($event, '', $eventDispatcher);
        self::assertSame(['testData'], $event->getDataFromApi());
    }


    public function testSaveDataDoNothingIfErrorIsNotSet(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setDataFromApi(['errorNOTset'=>false, 'content'=>'test']);
        $event->setCteId(12);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->saveData($event, '', $eventDispatcher);
    }


    public function testSaveDataDoNothingIfErrorIsTrue(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setDataFromApi(['error'=>true, 'content'=>'test']);
        $event->setCteId(12);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->saveData($event, '', $eventDispatcher);
    }


    public function testSaveDataDoNothingIfContentIsEmpty(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setDataFromApi(['error'=>false, 'content'=>'']);
        $event->setCteId(12);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->saveData($event, '', $eventDispatcher);
    }


    public function testSaveDataDoNothingIfContentIsNotSet(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setDataFromApi(['error'=>false, 'contentNOTset'=>'test']);
        $event->setCteId(12);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->saveData($event, '', $eventDispatcher);
    }


    public function testSaveDataDoNothingIfCteIdIsEmpty(): void
    {
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::never())->method('dispatch');
        $event              = new OnHandleDataEvent();
        $event->setDataFromApi(['error'=>false, 'content'=>'test']);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->saveData($event, '', $eventDispatcher);
    }


    public function testSaveDataCallsEventDispatcherIfErrorIsFalseAndContentAndCteIdAreNotEmpty(): void
    {
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function(OnSaveDataEvent $event) {
                    self::assertSame(['error'=>false, 'content'=>'testcontent'],$event->getData());
                    self::assertSame(12, $event->getCteId());

                    return true;
                }),
                self::equalTo(OnSaveDataEvent::NAME)
            );

        $event              = new OnHandleDataEvent();
        $event->setDataFromApi(['error'=>false, 'content'=>'testcontent']);
        $event->setCteId(12);
        $listener           = new OnHandleDataListener($this->jsonHelper);
        $listener->saveData($event, '', $eventDispatcher);

        self::assertSame('testcontent', $event->getContent());
    }
}
