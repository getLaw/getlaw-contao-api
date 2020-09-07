<?php
/**
 * @package     getlawclient
 * @filesource  tl_content.php
 * @since       18.08.2020 - 17:02
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */

/**
 * Set Tablename: tl_content
 */
$table = 'tl_content';

/**
 * Action
 */
$GLOBALS['TL_DCA'][$table]['list']['operations']['renew'] = [
    'label'             => &$GLOBALS['TL_LANG'][$table]['renew'],
    'href'              => 'key=renew',
    'icon'              => 'sync.svg',
    'button_callback'   => array(\Esit\Getlawclient\Classes\Contao\Callbacks\ButtonManager::class, 'addRenewButton'),
    'attributes'        => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG'][$table]['renewConfirm'] . '\'))return false;Backend.getScrollOffset()"'
];

/* Palettes */
$GLOBALS['TL_DCA'][$table]['palettes']['getlawtext'] = '{type_legend},type,headline;{getlaw_legend},getlawtextkey,getlawdisableautorenew;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop;';

/* Fields */
$GLOBALS['TL_DCA'][$table]['fields']['getlawtextkey'] = [
    'label'                 => &$GLOBALS['TL_LANG'][$table]['getlawtextkey'],
    'exclude'               => true,
    'inputType'             => 'text',
    'eval'                  => ['mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'long'],
    'sql'                   => "varchar(128) NOT NULL default ''"
];

$GLOBALS['TL_DCA'][$table]['fields']['getlawdisableautorenew'] = [
    'label'                 => &$GLOBALS['TL_LANG'][$table]['getlawdisableautorenew'],
    'exclude'               => true,
    'inputType'             => 'checkbox',
    'eval'                  => ['tl_class'=>'w50 m12'],
    'sql'                   => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA'][$table]['fields']['savedon'] = [
    'label'                 => &$GLOBALS['TL_LANG'][$table]['savedon'],
    'exclude'               => true,
    'flag'                  => 6,
    'inputType'             => 'text',
    'eval'                  => ['maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'natural'],
    'sql'                   => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA'][$table]['fields']['getlawdata'] = [
    'label'                 => &$GLOBALS['TL_LANG'][$table]['getlawdata'],
    'exclude'               => true,
    'inputType'             => 'text',
    'eval'                  => ['doNotShow'=>true],
    'sql'                   => "text NULL"
];