<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  HttpFactory.php
 * @since       18.08.2020 - 17:44
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Services\Factories;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class HttpFactory
 * @package Esit\Getlawclient\Classes\Factories
 */
class HttpFactory
{


    /**
     * Erstellte einen HttpClient
     * @return HttpClientInterface
     */
    public function getClient(): HttpClientInterface
    {
        return HttpClient::create();
    }
}
