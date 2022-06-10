<?php

/**
 * @package     getlawclient
 * @since       10.08.2021 - 17:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     LGPL
 */

declare(strict_types=1);

namespace Esit\Getlawclient\Classes\Contao\Callbacks;

use Contao\DataContainer;
use Contao\System;
use Esit\Getlawclient\Classes\Services\Helper\LoadDataHelper;

class TlContent
{
    /**
     * @var LoadDataHelper
     */
    protected $helper;


    public function __construct()
    {
        $this->helper = System::getContainer()->get(LoadDataHelper::class);
    }


    /**
     * Ruft das Laden des Textes vom getLaw-Server beim Speichern des Inhaltselements auf.
     * @param $dc
     */
    public function loadData($dc): void
    {
        $row = $dc->activeRecord;

        if ('getlawtext' === $row->type && '' !== $row->getlawtextkey) {
            $textkey            = (string)$row->getlawtextkey;
            $id                 = (int)$row->id;
            $getlawdata         = (string)$row->getlawdata;
            $savedon            = 0; // Beim Speichern immer neu laden
            $disableautorenew   = (bool)$row->getlawdisableautorenew;

            $this->helper->loadData($textkey, $id, $getlawdata, $savedon, $disableautorenew);
        }
    }


    /**
     * Entfernt den Speichern und schließen-Button, damit Fehlermelungen der API angezeigt werden können.
     * @param array         $buttons
     * @param DataContainer $dc
     * @return array
     */
    public function removeSaveAndExit(array $buttons, DataContainer $dc): array
    {
        // Remove the "Save and close" button
        if ('getlawtext' === $dc->activeRecord->type) {
            return ['save' => $buttons['save']];
        }

        return $buttons;
    }
}
