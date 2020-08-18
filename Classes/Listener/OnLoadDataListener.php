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
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;

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

    protected $logger;


    /**
     * OnLoadDataListener constructor.
     * @param HttpFactory $httpFactory
     * @param LogHelper   $logger
     */
    public function __construct(HttpFactory $httpFactory, LogHelper $logger)
    {
        $this->http     = $httpFactory->getClient();
        $this->logger   = $logger;
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
     * Lädt die Daten über die Schnittstelle.
     * @param OnLoadDataEvent $event
     */
    public function loadData(OnLoadDataEvent $event): void
    {
        try {
            $url        = $event->getUrl();
            $reponse    = $this->http->request('GET', $url);
            $content    = $reponse->getContent();
            $data       = \json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
            $event->setData($data);
        } catch (\Exception $e) {
            $this->logger->addError($e->getMessage(), __METHOD__);
        }
    }
}
