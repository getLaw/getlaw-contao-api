<?php

/**
 * @package     getlawclient
 * @since       09.06.2022 - 14:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2022
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Contao\Hooks;

use Contao\Widget;
use Esit\Getlawclient\Classes\Services\Helper\MessageHelper;

class RgxpHandler
{
    public const RGXP = 'apikey';

    /**
     * @var MessageHelper
     */
    protected MessageHelper $messageHelper;


    /**
     * @param MessageHelper $messageHelper
     */
    public function __construct(MessageHelper $messageHelper)
    {
        $this->messageHelper = $messageHelper;
    }


    /**
     * Behandelt den regulären Ausdruck für den apikey.
     * @param string $regexp
     * @param        $input
     * @param Widget $widget
     * @return bool
     */
    public function handleApiKey(string $regexp, $input, Widget $widget): bool
    {
        if (self::RGXP === $regexp) {
            $data['error']  = false; // Keinen Datenfehler melden!
            $lang           = $GLOBALS['TL_LANG']['MSC'];
            $error          = $this->messageHelper->generateMessage($data, $lang, $input, false);

            if ('' !== $error) {
                $widget->addError($error);
            }

            return true;
        }

        return false;
    }
}
