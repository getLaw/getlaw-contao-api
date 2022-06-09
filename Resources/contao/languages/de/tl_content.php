<?php
/**
 * @package     getlawclient
 * @filesource  tl_content.php
 * @since       18.08.2020 - 17:05
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */


/**
 * Set Tablename
 */
$table = 'tl_content';


/**
 * Operations
 */
$GLOBALS['TL_LANG'][$table]['renew']                    = ['Text manuell abrufen', 'Text manuell abrufen'];
$GLOBALS['TL_LANG'][$table]['renewConfirm']             = 'Wollen Sie den Text jetzt abrufen?';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['getlawtextkey']            = ['API-Schlüssel', 'Bitte geben Sie den individuellen API-Schlüssel Ihres Textes ein. Diesen erhalten Sie auf www.getLaw.de.'];
$GLOBALS['TL_LANG'][$table]['getlawdisableautorenew']   = ['Automatischen Abruf deaktivieren', 'Falls gewünscht, können Sie den automatischen Abruf durch Setzen eines Häkchens deaktivieren.'];
$GLOBALS['TL_LANG'][$table]['savedon']                  = ['Speicherzeit', 'Zeitpunkt an dem der Text das letzte Mal abgerufen und gespeichert wurde.'];
$GLOBALS['TL_LANG'][$table]['getlawdata']               = ['Daten der Schnittstelle', 'Daten die über die Schnittstelle geladen wurden.'];


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['getlaw_legend']    = 'getLaw';