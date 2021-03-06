<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  LogHelperTest.php
 * @since       19.08.2020 - 17:16
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Tests\Services\Helper;

use Contao\CoreBundle\Monolog\ContaoContext;
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Class LogHelperTest
 * @package Esit\Getlawclient\Tests\Services\Helper
 */
class LogHelperTest extends TestCase
{


    public function testAddError(): void
    {
        $msg    = 'TestErrorMessage';
        $place  = 'DemoClass::DemoMethode';
        $context= new ContaoContext($place, TL_ERROR);
        $logger = $this->getMockBuilder(Logger::class)
                       ->disableOriginalConstructor()
                       ->onlyMethods(['addError'])
                       ->getMock();

        $logger->expects(self::once())->method('addError')->with($msg, ['contao' => $context]);

        $helper = new LogHelper($logger);
        $helper->addError($msg, $place);
    }
}
