<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  JsonHelperTest.php
 * @since       19.08.2020 - 17:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */
namespace Esit\Getlawclient\Tests\Services\Helper;

use Contao\CoreBundle\Monolog\ContaoContext;
use Esit\Getlawclient\Classes\Services\Helper\JsonHelper;
use Esit\Getlawclient\Classes\Services\Helper\LogHelper;
use Esit\Getlawclient\EsitTestCase;
use Monolog\Logger;

/**
 * Class JsonHelperTest
 * @package Esit\Getlawclient\Tests\Services\Helper
 */
class JsonHelperTest extends EsitTestCase
{

    protected $logHelper;

    protected function setUp(): void
    {


        $this->logHelper = $this->getMockBuilder(LogHelper::class)
                                ->disableOriginalConstructor()
                                ->onlyMethods(['addError'])
                                ->getMock();
    }


    public function testDecodeReturnArrayIfStringIsNotEmpty(): void
    {
        $this->logHelper->expects(self::never())->method('addError');
        $helper = new JsonHelper($this->logHelper);
        $rtn    = $helper->decode('["test01"]');
        self::assertIsArray($rtn);
        self::assertNotEmpty($rtn);
        self::assertSame("test01", $rtn[0]);
    }


    public function testDecodeReturnEmptyArrayIfStringIsEmpty(): void
    {
        $this->logHelper->expects(self::never())->method('addError');
        $helper = new JsonHelper($this->logHelper);
        $rtn    = $helper->decode('');
        self::assertIsArray($rtn);
        self::assertEmpty($rtn);
    }


    public function testDecodeReturnCallLogHelperIfStringIsBroken(): void
    {
        $place  = 'Esit\Getlawclient\Classes\Services\Helper\JsonHelper::decode';
        $msg    = 'Control character error, possibly incorrectly encoded';
        $this->logHelper->expects(self::once())->method('addError')->with($msg, $place);
        $helper = new JsonHelper($this->logHelper);
        $rtn    = $helper->decode('["te][st');
        self::assertIsArray($rtn);
        self::assertEmpty($rtn);
    }


    public function testEncodeReturnsStringIfArrayIsNotEmpty(): void
    {
        $this->logHelper->expects(self::never())->method('addError');
        $helper = new JsonHelper($this->logHelper);
        $rtn    = $helper->encode(["test01"]);
        self::assertSame('["test01"]', $rtn);
    }


    public function testEncodeReturnsAEmptyStringIfArrayIsEmpty(): void
    {
        $this->logHelper->expects(self::never())->method('addError');
        $helper = new JsonHelper($this->logHelper);
        $rtn    = $helper->encode([]);
        self::assertEmpty($rtn);
    }


    public function testEncodeCallsLogHelperIfArrayIsBroken(): void
    {
        $place  = 'Esit\Getlawclient\Classes\Services\Helper\JsonHelper::encode';
        $msg    = 'Malformed UTF-8 characters, possibly incorrectly encoded';
        $this->logHelper->expects(self::once())->method('addError')->with($msg, $place);
        $helper = new JsonHelper($this->logHelper);
        $rtn    = $helper->encode([null=>"\xB1\x31"]);  // broken utf-8 sequence
        self::assertEmpty($rtn);
    }
}
