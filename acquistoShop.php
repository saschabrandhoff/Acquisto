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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */

class acquistoShop extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function getExportModules() {
        if ($handle = opendir(TL_ROOT . '/system/modules/acquistoShop/exportModules/'))
        {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != ".." && is_file(TL_ROOT . '/system/modules/acquistoShop/exportModules/' . $file))
                {
                    require_once(TL_ROOT . '/system/modules/acquistoShop/exportModules/' . $file);
                    $strModule = substr($file, 0, -4);
                    $objModule = new $strModule();
                    $arrModules[$strModule] = $objModule->getExportInfo();
                }
            }

            closedir($handle);
        }

        return $arrModules;
    }

    /**
     * Ermittelt Grundpreis anhand eines Produkts anhand der Menge
     **/
    public function getProductGrundpreis($id, $menge) {
        $objProdukt = $this->Database->prepare("SELECT id, inhalt, berechnungsmenge, mengeneinheit FROM tl_shop_produkte WHERE id = ?")
                           ->limit(1)
                           ->execute($id);
        $objEinheit = $this->Database->prepare("SELECT * FROM tl_shop_mengeneinheit WHERE id = ?")
                           ->limit(1)
                           ->execute($objProdukt->mengeneinheit);

        $dblPreis   = $this->getProductPrice($objProdukt->id, $menge);

        if($objProdukt->inhalt && $objProdukt->berechnungsmenge && $dblPreis) {
            $dblGrundpreis = ($dblPreis / $objProdukt->inhalt) * $objProdukt->berechnungsmenge;

            return array(
                'preis'            => sprintf("%01.2f", $dblPreis),
                'grundpreis'       => sprintf("%01.2f", $dblGrundpreis),
                'inhalt'           => $objProdukt->inhalt,
                'berechnungsmenge' => $objProdukt->berechnungsmenge,
                'mengeneinheit'    => $objEinheit->einheit
            );
        }
    }

    /**
     * Ermittelt Produktpreis anhand der Menge
     **/
    public function getProductPrice($id, $menge) {
        $objProdukt = $this->Database->prepare("SELECT type, preise FROM tl_shop_produkte WHERE id = ?")
                           ->limit(1)
                           ->execute($id);

        if(is_array($arrPreise = unserialize($objProdukt->preise))) {
            if(count($arrPreise) == 1) {
                $endpreis = $arrPreise[0]['label'];
            } else {
                foreach($arrPreise as $value) {
                    if($menge >= $value['value']) {
                        $endpreis = $value['label'];
                    }
                }
            }
        }

        return sprintf("%01.2f", $endpreis);
    }

    public function checkGroupPermissions($member_group, $groups = array()) {

    }

    public function generateOrderID($orderNumber) {
        $orderID = $GLOBALS['TL_CONFIG']['rechnungsformat'];
        $orderID = preg_replace("/\[Y]/", date("Y"), $orderID);
        $orderID = preg_replace("/\[y]/", date("y"), $orderID);
        $orderID = preg_replace("/\[m]/", date("m"), $orderID);
        $orderID = preg_replace("/\[d]/", date("d"), $orderID);

        preg_match("/\[C(.*)]/", $orderID, $arrMatch);
        $orderID = preg_replace("/\[C(.*)]/", sprintf("%0" . $arrMatch[1] . "d", $orderNumber), $orderID);

        return $orderID;
    }

    public function getInsertTagPID() {
        switch(VERSION) {
            case "2.10":
                return "{{env::page_id}}";
                break;;
            case "2.11":
                return "{{page::id}}";
                break;;
        }
    }
}

?>