<?php declare(strict_types = 1);
/**
 * @package     getlawclient
 * @since       10.08.2021 - 17:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2021
 * @license     EULA
 */
namespace Esit\Getlawclient\Classes\Contao\Callbacks;

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
        $this->helper = System::getContainer()->get('esit_getlawclient.services.helper.load_data_helper');
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
            $savedon            = (int)$row->savedon;
            $disableautorenew   = (bool)$row->getlawdisableautorenew;

            $this->helper->loadData($textkey, $id, $getlawdata, $savedon, $disableautorenew);
        }
    }
}
