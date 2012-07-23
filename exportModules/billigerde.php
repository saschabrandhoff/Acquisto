<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class billigerde extends Controller {
    protected $exportModule = 'billiger.de';
    public $exportID;

    public function __construct()
    {
        parent::__construct();
    }

    public function getSettingsData() {

    }

    public function getExportInfo() {
        return $this->exportModule;
    }

    public function compile()
    {
        $this->Import('Database');
        $this->Import('Environment');
        $this->Import('acquistoShop', 'Shop');

        $objExport = $this->Database->prepare("SELECT * FROM tl_shop_export WHERE id = ?")->limit(1)->execute($this->exportID);
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objExport->produktDetails);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        $htmlData .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $htmlData .= "<products>\n";

        $objProdukte = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE aktiv = ?;")->execute(1);
        while($objProdukte->next()) {
            $htmlData .= "    <product>\n";
            $htmlData .= "        <aid>" . $objProdukte->id . $objProdukte->produktnummer . "</aid>\n";
            $htmlData .= "        <name>" . $objProdukte->bezeichnung . "</name>\n";
            $htmlData .= "        <desc>" . $objProdukte->teaser . "</desc>\n";
            $htmlData .= "        <price>" . $this->Shop->getProductPrice($objProdukte->id, 0) . "</price>\n";
            $htmlData .= "        <link>http://" . $this->Environment->httpHost . sprintf($strUrl, $objProdukte->alias) . "</link>\n";

            if($objProdukte->hersteller) {
                $objHersteller = $this->Database->prepare("SELECT * FROM tl_hersteller WHERE id = ?;")->limit(1)->execute($objProdukte->hersteller);
                $htmlData .= "        <brand>" . $objHersteller->bezeichnung . "</brand>\n";
            }

//            $htmlData .= "        <ean>6417182976753</ean>\n";

            if($arrGrundpreis = $this->Shop->getProductGrundpreis($objProdukte->id, 0)) {
                $htmlData .= "        <ppu>" . str_replace(".", ",", $arrGrundpreis['grundpreis']) . " € / " . $arrGrundpreis['berechnungsmenge'] . " " . $arrGrundpreis['mengeneinheit'] . "</ppu>\n";
            }

//            $htmlData .= "        <shop_cat>Bücher;Geschichte</shop_cat>\n";

            if($objProdukte->preview_image) {
                $htmlData .= "            <image>http://" . $this->Environment->httpHost . "/" . $objProdukte->preview_image . "</image>\n";
            }

//            $htmlData .= "        <dlv_time>sofort lieferbar</dlv_time>\n";
//            $htmlData .= "        <dlv_cost>0,0</dlv_cost>\n";
            $htmlData .= "    </product>\n";

        }

        $htmlData .= "</products>\n";
        return $htmlData;
    }
}


?>