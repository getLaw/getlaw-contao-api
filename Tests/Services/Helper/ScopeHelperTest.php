<?php

/**
 * @package     getlawclient
 * @since       25.01.2024 - 13:35
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Tests\Services\Helper;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Esit\Getlawclient\Classes\Services\Helper\ScopeHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ScopeHelperTest extends TestCase
{


    /**
     * @var MockObject|(RequestStack&MockObject)
     */
    private $requestStack;


    /**
     * @var MockObject|(Request&MockObject)
     */
    private $request;


    /**
     * @var (ScopeMatcher&MockObject)|MockObject
     */
    private $scopeMatcher;


    /**
     * @var ScopeHelper
     */
    private ScopeHelper $helper;


    protected function setUp(): void
    {
        $this->requestStack = $this->getMockBuilder(RequestStack::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->request      = $this->getMockBuilder(Request::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->scopeMatcher = $this->getMockBuilder(ScopeMatcher::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->helper       = new ScopeHelper($this->requestStack, $this->scopeMatcher);
    }


    public function testIsBackendReturnFalseIfRequestIsNull(): void
    {
        $this->requestStack->expects(self::once())
                           ->method('getCurrentRequest')
                           ->willReturn(null);

        $this->scopeMatcher->expects(self::never())
                           ->method('isBackendRequest');

        $this->assertFalse($this->helper->isBackend());
    }


    public function testIsBackendReturnFalseIfRequestIsNoBackendRequest(): void
    {
        $this->requestStack->expects(self::once())
                           ->method('getCurrentRequest')
                           ->willReturn($this->request);

        $this->scopeMatcher->expects(self::once())
                           ->method('isBackendRequest')
                           ->with($this->request)
                           ->willReturn(false);

        $this->assertFalse($this->helper->isBackend());
    }


    public function testIsBackendReturnFalseIfRequestIsABackendRequest(): void
    {
        $this->requestStack->expects(self::once())
                           ->method('getCurrentRequest')
                           ->willReturn($this->request);

        $this->scopeMatcher->expects(self::once())
                           ->method('isBackendRequest')
                           ->with($this->request)
                           ->willReturn(true);

        $this->assertTrue($this->helper->isBackend());
    }


    public function testIsFrontendReturnFalseIfRequestIsNull(): void
    {
        $this->requestStack->expects(self::once())
                           ->method('getCurrentRequest')
                           ->willReturn(null);

        $this->scopeMatcher->expects(self::never())
                           ->method('isFrontendRequest');

        $this->assertFalse($this->helper->isFrontend());
    }


    public function testIsFrontendReturnFalseIfRequestIsNoFrontendRequest(): void
    {
        $this->requestStack->expects(self::once())
                           ->method('getCurrentRequest')
                           ->willReturn($this->request);

        $this->scopeMatcher->expects(self::once())
                           ->method('isFrontendRequest')
                           ->with($this->request)
                           ->willReturn(false);

        $this->assertFalse($this->helper->isFrontend());
    }


    public function testIsFrontendReturnFalseIfRequestIsAFrontendRequest(): void
    {
        $this->requestStack->expects(self::once())
                           ->method('getCurrentRequest')
                           ->willReturn($this->request);

        $this->scopeMatcher->expects(self::once())
                           ->method('isFrontendRequest')
                           ->with($this->request)
                           ->willReturn(true);

        $this->assertTrue($this->helper->isFrontend());
    }
}
