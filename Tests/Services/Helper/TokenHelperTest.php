<?php

/**
 * @package     getlawclient
 * @since       06.03.2024 - 15:25
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Getlawclient\Tests\Services\Helper;

use Esit\Getlawclient\Classes\Services\Helper\TokenHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TokenHelperTest extends TestCase
{

    public function testGetToken(): void
    {
        $tokenName      = 'test_token_123';
        $token          = \md5($tokenName);

        $tokenManager   = $this->getMockBuilder(CsrfTokenManagerInterface::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $csrfToken      = $this->getMockBuilder(CsrfToken::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $tokenManager->expects(self::once())
                     ->method('getToken')
                     ->with($tokenName)
                     ->willReturn($csrfToken);

        $csrfToken->expects(self::once())
                  ->method('getValue')
                  ->willReturn($token);

        $helper = new TokenHelper($tokenName, $tokenManager);

        $this->assertSame($token, $helper->getToken());
    }
}
