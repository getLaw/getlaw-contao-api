<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 *
 * @package     getlawclient
 * @filesource  config.php
 * @version     1.0.0
 * @since       18.08.2020 - 16:13
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

/**
 * BACK END MODULES
 */
$GLOBALS['BE_MOD']['content']['article']['renew']   = [\Esit\Getlawclient\Classes\Contao\Callbacks\ButtonManager::class, 'handleRenewText'];

/**
 * CONTENT ELEMENTS
 */
$GLOBALS['TL_CTE']['getlaw']['getlawtext']          = \Esit\Getlawclient\Classes\Contao\Elements\ContentGetlawText::class;


/**
 * getLaw-Settings
 */
$GLOBALS['getLaw']['server_url']                    = 'https://www.getlaw.de/api/texts/';
$GLOBALS['getLaw']['api_header']                    = 'X-getLaw-API-Version';
$GLOBALS['getLaw']['api_version']                   = '1';