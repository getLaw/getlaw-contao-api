<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  OnLoadDataListenerTest.php
 * @since       19.08.2020 - 17:56
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Tests\Listener;

use Esit\Getlawclient\Classes\Events\OnLoadDataEvent;
use Esit\Getlawclient\Classes\Listener\OnLoadDataListener;
use Esit\Getlawclient\Classes\Services\Factories\HttpFactory;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;
use Esit\Getlawclient\EsitTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class OnLoadDataListenerTest
 * @package Esit\Getlawclient\Tests\Listener
 */
class OnLoadDataListenerTest extends EsitTestCase
{


    protected $httpClient;

    protected $httpFactory;


    protected $logger;


    protected $jsonHelper;


    protected function setUp(): void
    {
        $this->httpFactory  = $this->getMockBuilder(HttpFactory::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['getClient'])
                                   ->getMock();

        $this->httpClient   = $this->getMockForAbstractClass(HttpClientInterface::class);

        $this->httpFactory->expects(self::once())->method('getClient')->willReturn($this->httpClient);

        $this->logger       = $this->getMockBuilder(LogHelper::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['addError'])
                                   ->getMock();
        $this->jsonHelper   = $this->getMockBuilder(JsonHelper::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['decode'])
                                   ->getMock();

    }


    public function testGenerateUrlSetEmptyStringIfBothStringAreEmpty(): void
    {
        $event      = new OnLoadDataEvent();
        $listner    = new OnLoadDataListener($this->httpFactory, $this->logger, $this->jsonHelper);
        $listner->generateUrl($event);
        self::assertEmpty($event->getUrl());
    }


    public function testGenerateUrlSetTextKeyIfGiven(): void
    {
        $listner    = new OnLoadDataListener($this->httpFactory, $this->logger, $this->jsonHelper);
        $event      = new OnLoadDataEvent();
        $event->setTextkey('textkey');
        $listner->generateUrl($event);
        self::assertEquals('textkey', $event->getUrl());
    }


    public function testGenerateUrlSetUrlIfGiven(): void
    {
        $listner    = new OnLoadDataListener($this->httpFactory, $this->logger, $this->jsonHelper);
        $event      = new OnLoadDataEvent();
        $event->setHost('https://example.org/');
        $listner->generateUrl($event);
        self::assertEquals('https://example.org/', $event->getUrl());
    }


    public function testGenerateUrlSetUrlAndTextkeyIfBothGiven(): void
    {
        $listner    = new OnLoadDataListener($this->httpFactory, $this->logger, $this->jsonHelper);
        $event      = new OnLoadDataEvent();
        $event->setHost('https://example.org/');
        $event->setTextkey('textkey');
        $listner->generateUrl($event);
        self::assertEquals('https://example.org/textkey', $event->getUrl());
    }


    public function testLoadDataCallRequestIfUrlIsGiven(): void
    {
        $reponse    = $this->getMockForAbstractClass(ResponseInterface::class);
        $reponse->expects(self::once())->method('getContent')->with(false)->willReturn('content');
        $this->httpClient->expects(self::once())->method('request')->with('GET', 'https://example.org/textkey')->willReturn($reponse);
        $this->jsonHelper->expects(self::once())->method('decode')->with('content')->willReturn(['newContent']);
        $listner    = new OnLoadDataListener($this->httpFactory, $this->logger, $this->jsonHelper);
        $event      = new OnLoadDataEvent();
        $event->setUrl('https://example.org/textkey');
        $listner->loadData($event);
        self::assertEquals(['newContent'], $event->getData());
    }


    public function testLoadDataCatchException(): void
    {
        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->willThrowException(
                new \Exception('Test Exeption')
            );

        $this->logger
            ->expects(self::once())
            ->method('addError')
            ->with('Test Exeption', 'Esit\Getlawclient\Classes\Listener\OnLoadDataListener::loadData');

        $listner    = new OnLoadDataListener($this->httpFactory, $this->logger, $this->jsonHelper);
        $event      = new OnLoadDataEvent();
        $listner->loadData($event);
    }
}
