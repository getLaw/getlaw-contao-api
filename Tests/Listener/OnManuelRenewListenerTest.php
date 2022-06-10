<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  OnManuelRenewListenerTest.php
 * @since       07.09.2020 - 16:00
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */
namespace Esit\Getlawclient\Tests\Listener;

use Doctrine\DBAL\Connection;
use Esit\Getlawclient\Classes\Events\OnHandleDataEvent;
use Esit\Getlawclient\Classes\Events\OnManualRenewEvent;
use Esit\Getlawclient\Classes\Listener\OnManualRenewListener;
use Esit\Getlawclient\Classes\Services\Helper\ContaoHelper;
use Esit\Getlawclient\EsitTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class OnManuelRenewListenerTest
 * @package Esit\Getlawclient\Tests\Listener
 */
class OnManuelRenewListenerTest extends EsitTestCase
{


    /**
     * @var Connection
     */
    protected $connection;


    /**
     * @var ContaoHelper
     */
    protected $ctoHelper;


    /**
     * @var EventDispatcher
     */
    protected $di;


    protected function setUp(): void
    {
        $this->connection   = $this->getConnectionMock();
        $this->ctoHelper    = $this->getMockBuilder(ContaoHelper::class)->getMock();
        $this->di           = $this->getMockBuilder(EventDispatcher::class)->disableOriginalConstructor()->getMock();
    }


    public function testLoadCteDoNothingIfIdIsEmpty(): void
    {
        $query      = $this->getQueryBuilderMock();
        $connection = $this->getConnectionMock($query);
        $query->expects(self::never())->method('select');
        $listener   = new OnManualRenewListener($connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $event->setTable('tl_test');

        $listener->loadCte($event);
    }


    public function testLoadCteDoNothingIfTableIsEmpty(): void
    {
        $query      = $this->getQueryBuilderMock();
        $connection = $this->getConnectionMock($query);
        $query->expects(self::never())->method('select');
        $listener   = new OnManualRenewListener($connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $event->setCteId(12);

        $listener->loadCte($event);
    }


    public function testLoadCteCallQueryIfTableAndIdAreSet(): void
    {
        $table      = 'tl_test';
        $id         = 12;
        $query      = $this->getQueryBuilderMock();
        $this->addMethodeMock($query, 'select', '*');
        $this->addMethodeMock($query, 'from', $table);
        $this->addMethodeMock($query, 'where', "id = $id");
        $connection = $this->getConnectionMock($query);
        $listener   = new OnManualRenewListener($connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $event->setTable($table);
        $event->setCteId($id);

        $listener->loadCte($event);

        self::assertEmpty($event->getTextkey());
        self::assertEmpty($event->getArticleId());
    }


    public function testLoadCteSetTextkeyIfNotEmpty(): void
    {
        $table      = 'tl_test';
        $id         = 12;
        $key        = '987654321-13456789';
        $query      = $this->getQueryBuilderMock(['getlawtextkey'=>$key]);
        $this->addMethodeMock($query, 'select', '*');
        $this->addMethodeMock($query, 'from', $table);
        $this->addMethodeMock($query, 'where', "id = $id");
        $connection = $this->getConnectionMock($query);
        $listener   = new OnManualRenewListener($connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $event->setTable($table);
        $event->setCteId($id);

        $listener->loadCte($event);

        self::assertSame($key, $event->getTextkey());
        self::assertEmpty($event->getArticleId());
    }


    public function testLoadCteSetArticleIdIfNotEmpty(): void
    {
        $table      = 'tl_test';
        $id         = 12;
        $article    = 48;
        $query      = $this->getQueryBuilderMock(['pid'=>$article]);
        $this->addMethodeMock($query, 'select', '*');
        $this->addMethodeMock($query, 'from', $table);
        $this->addMethodeMock($query, 'where', "id = $id");
        $connection = $this->getConnectionMock($query);
        $listener   = new OnManualRenewListener($connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $event->setTable($table);
        $event->setCteId($id);

        $listener->loadCte($event);

        self::assertEmpty($event->getTextkey());
        self::assertSame($article, $event->getArticleId());
    }


    public function testLoadTextDoNothingIfCteIdIsEmpty(): void
    {
        $cteId          = 0;
        $textkey        = '12346798-987654321';
        $getlawServer   = 'https://example.org/';
        $header         = 'X-My-Test-Header';
        $version        = '1';
        $name           = 'on.test.my.methode';
        $this->di->expects(self::never())->method('dispatch');
        $listener       = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event          = new OnManualRenewEvent();
        $event->setCteId($cteId);
        $event->setTextkey($textkey);
        $event->setGetlawServer($getlawServer);
        $event->setGetlawHeader($header);
        $event->setApiVersion($version);
        $listener->loadText($event, $name, $this->di);
    }


    public function testLoadTextDoNothingIfTextkeyIsEmpty(): void
    {
        $cteId          = 12;
        $textkey        = '';
        $getlawServer   = 'https://example.org/';
        $header         = 'X-My-Test-Header';
        $version        = '1';
        $name           = 'on.test.my.methode';
        $this->di->expects(self::never())->method('dispatch');
        $listener       = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event          = new OnManualRenewEvent();
        $event->setCteId($cteId);
        $event->setTextkey($textkey);
        $event->setGetlawServer($getlawServer);
        $event->setGetlawHeader($header);
        $event->setApiVersion($version);
        $listener->loadText($event, $name, $this->di);
    }


    public function testLoadTextDoNothingIfServerIsEmpty(): void
    {
        $cteId          = 12;
        $textkey        = '12346798-987654321';
        $getlawServer   = '';
        $header         = 'X-My-Test-Header';
        $version        = '1';
        $name           = 'on.test.my.methode';
        $this->di->expects(self::never())->method('dispatch');
        $listener       = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event          = new OnManualRenewEvent();
        $event->setCteId($cteId);
        $event->setTextkey($textkey);
        $event->setGetlawServer($getlawServer);
        $event->setGetlawHeader($header);
        $event->setApiVersion($version);
        $listener->loadText($event, $name, $this->di);
    }


    public function testLoadTextDoNothingIfHeaderIsEmpty(): void
    {
        $cteId          = 12;
        $textkey        = '12346798-987654321';
        $getlawServer   = 'https://example.org/';
        $header         = '';
        $version        = '1';
        $name           = 'on.test.my.methode';
        $this->di->expects(self::never())->method('dispatch');
        $listener       = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event          = new OnManualRenewEvent();
        $event->setCteId($cteId);
        $event->setTextkey($textkey);
        $event->setGetlawServer($getlawServer);
        $event->setGetlawHeader($header);
        $event->setApiVersion($version);
        $listener->loadText($event, $name, $this->di);
    }


    public function testLoadTextDoNothingIfVersionIsEmpty(): void
    {
        $cteId          = 12;
        $textkey        = '12346798-987654321';
        $getlawServer   = 'https://example.org/';
        $header         = 'X-My-Test-Header';
        $version        = '';
        $name           = 'on.test.my.methode';
        $this->di->expects(self::never())->method('dispatch');
        $listener       = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event          = new OnManualRenewEvent();
        $event->setCteId($cteId);
        $event->setTextkey($textkey);
        $event->setGetlawServer($getlawServer);
        $event->setGetlawHeader($header);
        $event->setApiVersion($version);
        $listener->loadText($event, $name, $this->di);
    }


    public function testLoadTextCallDispatcherIfAllParametersGiven(): void
    {
        $cteId          = 12;
        $textkey        = '12346798-987654321';
        $getlawServer   = 'https://example.org/';
        $header         = 'X-My-Test-Header';
        $version        = '1';
        $name           = 'on.test.my.methode';
        $this->di->expects(self::once())->method('dispatch')->with(
            self::callback(static function (OnHandleDataEvent $event) {
                self::assertSame('https://example.org/', $event->getGetlawServer());
                self::assertSame('X-My-Test-Header', $event->getGetlawHeader());
                self::assertSame('1', $event->getApiVersion());
                self::assertSame('12346798-987654321', $event->getTextkey());
                self::assertSame(12, $event->getCteId());
                self::assertTrue($event->getManualRenew());
                $event->setDataFromApi(['test']);

                return true;
            })
        );
        $listener       = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event          = new OnManualRenewEvent();
        $event->setLang([]);
        $event->setCteId($cteId);
        $event->setTextkey($textkey);
        $event->setGetlawServer($getlawServer);
        $event->setGetlawHeader($header);
        $event->setApiVersion($version);
        $listener->loadText($event, $name, $this->di);
        self::assertSame(['test'], $event->getDataFromApi());
    }


    public function testRedirectDoNothingIfArticleIdIsEmpty(): void
    {
        $this->ctoHelper->expects(self::never())->method('redirect');
        $listener   = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $listener->redirect($event);
    }


    public function testRedirectCallRedirectHelperIfArticleIdIsNotEmpty(): void
    {
        $pid        = 12;
        $url        = "contao?do=article&table=tl_content&id=$pid";
        $this->ctoHelper->expects(self::once())->method('redirect')->with($url);
        $listener   = new OnManualRenewListener($this->connection, $this->ctoHelper);
        $event      = new OnManualRenewEvent();
        $event->setArticleId($pid);
        $listener->redirect($event);
    }
}
