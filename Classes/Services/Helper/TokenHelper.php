<?php

/**
 * @package     getlawclient
 * @since       06.03.2024 - 15:08
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Services\Helper;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TokenHelper
{
    /**
     * @var string
     */
    private string $tokenName;


    /**
     * @var CsrfTokenManagerInterface
     */
    private CsrfTokenManagerInterface $tokenManager;


    /**
     * @param string $tokenName
     * @param CsrfTokenManagerInterface $tokenManager
     */
    public function __construct(string $tokenName, CsrfTokenManagerInterface $tokenManager)
    {
        $this->tokenName    = $tokenName;
        $this->tokenManager = $tokenManager;
    }


    /**
     * Gibt einen Token zurück.
     * @return string
     */
    public function getToken(): string
    {
        return $this->tokenManager->getToken($this->tokenName)->getValue();
    }
}
