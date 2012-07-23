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

/**
 * Class acquistoShopGutschein
 *
 * Front end module "acquistoShopGutschein".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class acquistoShopGutschein extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function validateGutschein($id, $member_id = 0) {
        $objGutschein = $this->Database->prepare("SELECT * FROM tl_shop_gutscheine WHERE id = ?")->execute($id);

        $boolUseable = true;
        if($objGutschein->kunden_id) {
            if($objGutschein->kunden_id != $member_id) {
                $boolUseable = false;
            }
        }

        if($objGutschein->zeitgrenze) {
            if($objGutschein->gueltig_von > time() OR $objGutschein->gueltig_bis < time()) {
                $boolUseable = false;
            }
        }

        if($objGutschein->using_counter) {
            if($objGutschein->max_using <= $objGutschein->is_used) {
                $boolUseable = false;
            }
        }

        if(!$objGutschein->using_counter && $objGutschein->is_used) {
            $boolUseable = false;
        }

        if(!$objGutschein->using_counter && $objGutschein->is_used) {
            $boolUseable = false;
        }

        if(!$objGutschein->aktiv) {
            $boolUseable = false;
        }

        return $boolUseable;
    }

    public function addGutschein2Use($id) {
        $_SESSION['acquistoGutscheine'][$id] = $id;
    }

    public function removeGutschein($id) {
        unset($_SESSION['acquistoGutscheine'][$id]);
    }

    public function getGutschein($id) {
        $objGutschein = $this->Database->prepare("SELECT * FROM tl_shop_gutscheine WHERE id = ?")->limit(1)->execute($id);
        $objObject = new stdClass();
        $objObject = (object) $objGutschein->row();
        $objObject->gueltig_von = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objObject->gueltig_von);
        $objObject->gueltig_bis = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objObject->gueltig_bis);
        $objObject->preis       = sprintf("%01.2f", $objObject->preis);

        return $objObject;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function getList() {
        if(is_array($_SESSION['acquistoGutscheine'])) {
            foreach($_SESSION['acquistoGutscheine'] as $id) {
                $arrGutscheine[] = $this->getGutschein($id);
            }
        }

        return $arrGutscheine;
    }

    public function Checkout($member_id) {
        if(is_array($_SESSION['acquistoGutscheine'])) {
            foreach($_SESSION['acquistoGutscheine'] as $id) {
                if($this->validateGutschein($id, $member_id)) {
                    $this->Database->prepare("UPDATE tl_shop_gutscheine SET is_used = (is_used + 1) WHERE id = ?")->execute($id);
                    $arrReturn[] = $objGutschein = $this->getGutschein($id);

                }

                $this->removeGutschein($id);
            }

            return $arrReturn;
        }
    }
}

?>