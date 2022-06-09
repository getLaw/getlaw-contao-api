<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  OnSaveDataListenerTest.php
 * @since       20.08.2020 - 11:38
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */
namespace Esit\Getlawclient\Tests\Listener;

use Doctrine\DBAL\Query\QueryBuilder;
use Esit\Getlawclient\Classes\Events\OnSaveDataEvent;
use Esit\Getlawclient\Classes\Listener\OnSaveDataListener;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Esit\Getlawclient\EsitTestCase;

/**
 * Class OnSaveDataListenerTest
 * @package Esit\Getlawclient\Tests\Listener
 */
class OnSaveDataListenerTest extends EsitTestCase
{


    protected $jsonHelper;


    protected $connection;


    protected function setUp(): void
    {
        $this->jsonHelper   = $this->getMockBuilder(JsonHelper::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['encode'])
                                   ->getMock();

        $this->connection   = $this->getConnectionMock();
    }


    public function testGenerateJsonDoNothingIfDataIsEmpty(): void
    {
        $this->jsonHelper->expects(self::never())->method('encode');
        $event      = new OnSaveDataEvent();
        $listener   = new OnSaveDataListener($this->connection, $this->jsonHelper);
        $listener->generateJson($event);
    }


    public function testGenerateJsonDoNothingIfErrorIsNotSet(): void
    {
        $this->jsonHelper->expects(self::never())->method('encode');
        $event      = new OnSaveDataEvent();
        $event->setData(['test'=>'test']);
        $listener   = new OnSaveDataListener($this->connection, $this->jsonHelper);
        $listener->generateJson($event);
    }


    public function testGenerateJsonDoNothingIfErrorIsTrue(): void
    {
        $this->jsonHelper->expects(self::never())->method('encode');
        $event      = new OnSaveDataEvent();
        $event->setData(['error'=>true]);
        $listener   = new OnSaveDataListener($this->connection, $this->jsonHelper);
        $listener->generateJson($event);
    }


    public function testGenerateJsonDoNothingIfErrorIsNotFalse(): void
    {
        $this->jsonHelper->expects(self::never())->method('encode');
        $event      = new OnSaveDataEvent();
        $event->setData(['error'=>'notFalse!']);
        $listener   = new OnSaveDataListener($this->connection, $this->jsonHelper);
        $listener->generateJson($event);
    }


    public function testGenerateJsonCallsJsonHelperIfErrorIsFalse(): void
    {
        $data       = ['error'=>false, 'data'=>'testdata'];
        $this->jsonHelper->expects(self::once())->method('encode')->with($data)->willReturn('jsonString');
        $event      = new OnSaveDataEvent();
        $event->setData($data);
        $listener   = new OnSaveDataListener($this->connection, $this->jsonHelper);
        $listener->generateJson($event);
        self::assertSame('jsonString', $event->getJson());
    }


    public function testSaveDataCallsDatabase(): void
    {
        $query = $this->getMockBuilder(QueryBuilder::class)
                      ->disableOriginalConstructor()
                      ->onlyMethods(['update', 'set', 'setParameters', 'where', 'execute'])
                      ->getMock();

        $query->expects(self::once())
              ->method('update')
              ->with('mytestTable')
              ->willReturn($query);

        $query->expects(self::exactly(2))
              ->method('set')
              ->withConsecutive(['myTestTimeField', '?'], ['myTestDataField', '?'])
              ->willReturn($query);

        $query->expects(self::once())
              ->method('setParameters')
              ->with([\time(), 'testJson'])
              ->willReturn($query);

        $query->expects(self::once())
              ->method('where')
              ->with('id = 12')
              ->willReturn($query);

        $query->expects(self::once())
              ->method('execute');

        $connection = $this->getConnectionMock($query);
        $listener   = new OnSaveDataListener($connection, $this->jsonHelper);
        $event      = new OnSaveDataEvent();
        $event->setCteId(12);
        $event->setJson('testJson');
        $event->setTimeField('myTestTimeField');
        $event->setDataField('myTestDataField');
        $event->setTable('mytestTable');
        $listener->saveData($event);
    }
}
