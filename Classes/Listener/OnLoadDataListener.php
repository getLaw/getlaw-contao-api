<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  OnLoadDataListener.php
 * @since       18.08.2020 - 17:38
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Listener;

use Esit\Getlawclient\Classes\Events\OnLoadDataEvent;
use Esit\Getlawclient\Classes\Services\Factories\HttpFactory;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class OnLoadDataListener
 * @package Esit\Getlawclient\Classes\Listener
 */
class OnLoadDataListener
{


    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    protected $http;


    /**
     * @var LogHelper
     */
    protected $logger;


    /**
     * @var JsonHelper
     */
    protected $jsonHelper;


    /**
     * OnLoadDataListener constructor.
     * @param HttpFactory $httpFactory
     * @param LogHelper   $logger
     * @param JsonHelper  $jsonHelper
     */
    public function __construct(HttpFactory $httpFactory, LogHelper $logger, JsonHelper $jsonHelper)
    {
        $this->http         = $httpFactory->getClient();
        $this->logger       = $logger;
        $this->jsonHelper   = $jsonHelper;
    }


    /**
     * Erstellt die Url des Textes.
     * @param OnLoadDataEvent $event
     */
    public function generateUrl(OnLoadDataEvent $event): void
    {
        $host   = $event->getHost();
        $key    = $event->getTextkey();
        $event->setUrl($host . $key);
    }


    /**
     * @param  OnLoadDataEvent               $event
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function loadData(OnLoadDataEvent $event): void
    {
        try {
            $url        = $event->getUrl();
            $headerName = $event->getGetlawHeader();
            $apiVersion = $event->getApiVersion();
            $header     = [];

            if ('' !== $headerName && '' !== $apiVersion) {
                $header = ['headers' => [$headerName => $apiVersion]];
            }

            $reponse    = $this->http->request('GET', $url, $header);
            $content    = $reponse->getContent(false);
            $data       = $this->jsonHelper->decode($content);
            $event->setData($data);
        } catch (\Exception $e) {
            $this->logger->addError($e->getMessage(), __METHOD__);
        }
    }
}
