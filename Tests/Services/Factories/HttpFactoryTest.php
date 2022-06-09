<?php
declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  HttpFactoryTest.php
 * @since       19.08.2020 - 17:13
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */
namespace Esit\Getlawclient\Tests\Services\Factories;

use Esit\Getlawclient\Classes\Services\Factories\HttpFactory;
use Esit\Getlawclient\EsitTestCase;

/**
 * Class HttpFactoryTest
 * @package Esit\Getlawclient\Tests\Services\Factories
 */
class HttpFactoryTest extends EsitTestCase
{


    public function testGetClient(): void
    {
        $factory    = new HttpFactory();
        $client     = $factory->getClient();
        $this->assertNotNull($client);
    }
}
