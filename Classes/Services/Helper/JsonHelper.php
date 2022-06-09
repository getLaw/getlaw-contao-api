<?php

/**
 * @package     getlawclient
 * @filesource  JsonHelper.php
 * @since       19.08.2020 - 15:38
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Services\Helper;

/**
 * Class JsonHelper
 * @package Esit\Getlawclient\Classes\Services\Helper
 */
class JsonHelper
{
    /**
     * @var LogHelper
     */
    protected $logger;


    /**
     * JsonHelper constructor.
     * @param LogHelper $logHelper
     */
    public function __construct(LogHelper $logHelper)
    {
        $this->logger = $logHelper;
    }


    /**
     * Deserialisiert einen JSON-String.
     * @param  string $json
     * @return array
     */
    public function decode(string $json): array
    {
        $data = [];

        if (!empty($json)) {
            try {
                $data = \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $this->logger->addError($e->getMessage(), __METHOD__);
            }
        }

        return $data;
    }


    /**
     * Wandelt ein Array in einen JSON-String um.
     * @param  array  $data
     * @return string
     */
    public function encode(array $data): string
    {
        $json = '';

        if (!empty($data)) {
            try {
                $json = \json_encode($data, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $this->logger->addError($e->getMessage(), __METHOD__);
            }
        }

        return $json;
    }
}
