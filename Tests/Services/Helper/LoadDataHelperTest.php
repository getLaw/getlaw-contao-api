<?php
declare(strict_types=1);
/**
 * @package     getlawclient
 * @since       10.08.2021 - 18:11
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     EULA
 */
namespace Esit\Getlawclient\Tests\Services\Helper;

use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Esit\Getlawclient\Classes\Services\Helper\LoadDataHelper;
use Esit\Getlawclient\EsitTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadDataHelperTest extends EsitTestCase
{


    public function testLoadDataWithDefaultData(): void
    {
        $textkey            = 'getlawtextkey';
        $id                 = 12;
        $getlawdata         = 'getlawdata';
        $savedon            = 123456;
        $disableautorenew   = true;
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(function(OnHandleDataEvent $event) {
                    self::assertEquals('', $event->getContent());
                    self::assertEquals('https://www.getlaw.de/api/texts/', $event->getGetlawServer());
                    self::assertEquals('X-getLaw-API-Version', $event->getGetlawHeader());
                    self::assertEquals('1', $event->getApiVersion());
                    self::assertEquals('getlawtextkey', $event->getTextkey());
                    self::assertEquals('getlawdata', $event->getDataStingFromDb());
                    self::assertEquals('123456', $event->getSavedOn());
                    self::assertEquals(12, $event->getCteId());
                    self::assertEquals(true, $event->getDisableRenew());
                    self::assertEquals(false, $event->getManualRenew());

                    return true;
                }),
                $this->equalTo('getlaw.on.handle.data.event')
            );

        $helper = new LoadDataHelper($eventDispatcher);
        $helper->loadData($textkey, $id, $getlawdata, $savedon, $disableautorenew);
    }


    public function testLoadDataWithIndividualData(): void
    {
        $GLOBALS['TL_LANG']['MSC']['nodata']    = 'My Test Data';
        $GLOBALS['getLaw']['server_url']        = 'https://easySolutionsIT.de/';
        $GLOBALS['getLaw']['api_header']        = 'X-My-Test-Header';
        $GLOBALS['getLaw']['api_version']       = '2';

        $textkey            = 'getlawtextkey';
        $id                 = 12;
        $getlawdata         = 'getlawdata';
        $savedon            = 123456;
        $disableautorenew   = true;
        $eventDispatcher    = $this->createMock(EventDispatcherInterface::class);

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(function(OnHandleDataEvent $event) {
                    self::assertEquals('My Test Data', $event->getContent());
                    self::assertEquals('https://easySolutionsIT.de/', $event->getGetlawServer());
                    self::assertEquals('X-My-Test-Header', $event->getGetlawHeader());
                    self::assertEquals('2', $event->getApiVersion());
                    self::assertEquals('getlawtextkey', $event->getTextkey());
                    self::assertEquals('getlawdata', $event->getDataStingFromDb());
                    self::assertEquals('123456', $event->getSavedOn());
                    self::assertEquals(12, $event->getCteId());
                    self::assertEquals(true, $event->getDisableRenew());
                    self::assertEquals(false, $event->getManualRenew());

                    return true;
                }),
                $this->equalTo('getlaw.on.handle.data.event')
            );

        $helper = new LoadDataHelper($eventDispatcher);
        $helper->loadData($textkey, $id, $getlawdata, $savedon, $disableautorenew);
    }
}
