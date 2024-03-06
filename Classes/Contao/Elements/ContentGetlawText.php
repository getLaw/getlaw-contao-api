<?php

/**
 * @package     getlawclient
 * @filesource  ContentGetlawText.php
 * @since       18.08.2020 - 17:00
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Contao\Elements;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\System;
use Esit\Getlawclient\Classes\Services\Helper\LoadDataHelper;
use Esit\Getlawclient\Classes\Services\Helper\ScopeHelper;

/**
 * Class ContentGetlawText
 * @package Getlawclient\Classes\Contao\Elements
 */
class ContentGetlawText extends ContentElement
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_getlaw_text';


    /**
     * @var LoadDataHelper
     */
    protected $helper;


    /**
     * Generate the content element
     */
    protected function compile(): void
    {
        $scopeHelper = System::getContainer()->get(ScopeHelper::class);

        if (true === $scopeHelper?->isBackend()) {
            $this->genBeOutput();
        } else {
            $this->helper = System::getContainer()->get(LoadDataHelper::class);
            $this->genFeOutput();
        }
    }


    /**
     * Erzeugt die Ausgabe für das Backend.
     */
    protected function genBeOutput(): void
    {
        $this->strTemplate        = 'be_wildcard';
        $this->Template           = new BackendTemplate($this->strTemplate);
        $this->Template->title    = $this->headline;
        $this->Template->wildcard = '### ContentGetlawText ###';
    }


    /**
     * Erzeugt die Ausgabe für das Frontend.
     */
    protected function genFeOutput(): void
    {
        $textkey            = (string)$this->getlawtextkey;
        $id                 = (int)$this->id;
        $getlawdata         = (string)$this->getlawdata;
        $savedon            = (int)$this->savedon;
        $disableautorenew   = (bool)$this->getlawdisableautorenew;

        $this->Template->content = $this->helper->loadData($textkey, $id, $getlawdata, $savedon, $disableautorenew);
    }
}
