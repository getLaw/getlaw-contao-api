<?php

/**
 * @package     getlawclient
 * @since       18.08.2020 - 17:00
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2024
 * @license     EULA
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Services\Helper;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

class ScopeHelper
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;


    /**
     * @var ScopeMatcher
     */
    private ScopeMatcher $scopeMatcher;


    /**
     * @param RequestStack $requestStack
     * @param ScopeMatcher $scopeMatcher
     */
    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher)
    {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }


    /**
     * @return bool
     */
    public function isBackend(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return false;
        }

        return $this->scopeMatcher->isBackendRequest($request);
    }


    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return false;
        }

        return $this->scopeMatcher->isFrontendRequest($request);
    }
}
