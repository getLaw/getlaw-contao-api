<?php declare(strict_types=1);
/**
 * @package     getlawclient
 * @filesource  default.php
 * @since       18.08.2020 - 17:04
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

/**
 * Content Elemente
 */
$GLOBALS['TL_LANG']['CTE']['getlaw']            = ['getLaw', 'getLaw'];
$GLOBALS['TL_LANG']['CTE']['getlawtext']        = ['getLaw - Text', 'getLaw - Text'];


/**
 * Misc
 */
$GLOBALS['TL_LANG']['MSC']['nodata']            = 'Keinen Text gefunden.';
$GLOBALS['TL_LANG']['MSC']['apiSuccess']        = 'Laden des Text war erfolgreich.';
$GLOBALS['TL_LANG']['MSC']['apiError']          = 'Beim Laden des Textes ist ein Fehler aufgetreten.';
$GLOBALS['TL_LANG']['MSC']['apiErrorNoKey']     = 'Es wurde noch kein API-Schlüssel eingetragen.';
$GLOBALS['TL_LANG']['MSC']['apiErrorTooShort']  = 'Der API-Schlüssel ist zu kurz.';
$GLOBALS['TL_LANG']['MSC']['apiErrorTooLong']   = 'Der API-Schlüssel ist zu lang.';
$GLOBALS['TL_LANG']['MSC']['apiErrorWorngChar'] = 'Der API-Schlüssel enthält ungültige Zeichen.';
$GLOBALS['TL_LANG']['MSC']['apiErrorNotFound']  = 'Der API-Schlüssel ist falsch.';