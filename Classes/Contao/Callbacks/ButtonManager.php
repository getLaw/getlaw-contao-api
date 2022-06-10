<?php

/**
 * @package     getlawclient
 * @filesource  ButtonManager.php
 * @since       07.09.2020 - 08:58
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Contao\Callbacks;

use Contao\Controller;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;
use Esit\Getlawclient\Classes\Events\OnManualRenewEvent;

/**
 * Class ButtonManager
 * @package Esit\Getlawclient\Classes\Contao\Callbacks
 */
class ButtonManager
{
    /**
     * Erstellt den Button für das Abrufen des Textes,
     * wenn es sich um ein CTE vom Typ "getlawtext" handelt.
     * @param $row
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     * @param $strTable
     * @param $arrRootIds
     * @param $arrChildRecordIds
     * @param $blnCircularReference
     * @param $strPrevious
     * @param $strNext
     * @return string
     */
    public function addRenewButton(
        $row,
        $href,
        $label,
        $title,
        $icon,
        $attributes,
        $strTable,
        $arrRootIds,
        $arrChildRecordIds,
        $blnCircularReference,
        $strPrevious,
        $strNext
    ): string {
        $link = '';

        if ('getlawtext' === $row['type']) {
            $container    = System::getContainer();
            $tokenManager = $container->get('security.csrf.token_manager');
            $tokenName    = $container->getParameter('contao.csrf_token_name');
            $href .= '&id=' . $row['id'];

            if (null !== $tokenManager) {
                $href .= '&rt=' . $tokenManager->getToken($tokenName)->getValue();
            }

            $link = '<a href="' . Controller::addToUrl($href) . '" title="';
            $link .= StringUtil::specialchars($title) . '"' . $attributes . '>';
            $link .= Image::getHtml($icon, $label) . '</a> ';
        }

        return $link;
    }


    /**
     * Ruft das manuelle Laden des Textes auf.
     * @param $dc
     */
    public function handleRenewText($dc): void
    {
        $id             = $dc->id;
        $table          = $dc->table;
        $container      = System::getContainer();
        $di             = $container->get('event_dispatcher');
        $getlawServer   = $GLOBALS['getLaw']['server_url'] ?: 'https://www.getlaw.de/api/texts/';
        $getlawHeader   = $GLOBALS['getLaw']['api_header'] ?: 'X-getLaw-API-Version';
        $apiVersion     = $GLOBALS['getLaw']['api_version'] ?: '1';
        $lang           = $GLOBALS['TL_LANG']['MSC'];

        if (null !== $di) {
            $event = new OnManualRenewEvent();
            $event->setLang($lang);
            $event->setTable($table);
            $event->setCteId((int)$id);
            $event->setTable($table);
            $event->setGetlawServer($getlawServer);
            $event->setApiVersion($apiVersion);
            $event->setGetlawHeader($getlawHeader);
            $di->dispatch($event);
        }
    }
}
