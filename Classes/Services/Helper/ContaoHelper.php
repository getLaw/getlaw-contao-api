<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @filesource  ContaoHelper.php
 * @since       07.09.2020 - 17:04
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Services\Helper;

use Contao\Controller;
use Contao\Message;

/**
 * Class ContaoHelper
 * @package Esit\Getlawclient\Classes\Services\Helper
 */
class ContaoHelper
{


    /**
     * Zeigt dem Benutzer eine Fehlermeldung an.
     * @param string $msg
     */
    public function addError(string $msg): void
    {
        Message::addError($msg);
    }


    /**
     * Zeigt dem Benutzer eine Erfolgsmeldung an.
     * @param string $msg
     */
    public function addCornfirmation(string $msg): void
    {
        Message::addConfirmation($msg);
    }


    /**
     * Leitet zur übergebenen Url um.
     * @param string $url
     */
    public function redirect(string $url): void
    {
        Controller::redirect($url);
    }
}
