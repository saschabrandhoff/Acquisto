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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */

/**
 * Class acquistoShopBasket
 *
 * Front end module "acquistoShopBasket".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class acquistoShopBasket extends Controller {
    private $dataArray = array();
    
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
        $this->Import('acquistoShopProdukt', 'Produkt');
        
        $this->buildBasket();               
    }
    
    private function buildBasket() {
        $this->dataArray = array();
        if(is_array($_SESSION['acquistoShop'])) {
            foreach($_SESSION['acquistoShop'] as $id => $data) {
                if(is_array($data)) {
                    foreach($data as $attributes => $anzahl) {
                        $this->dataArray[md5($id . $attributes)] = array(
                            'id'         => $id,
                            'attributes' => $attributes,
                            'menge'      => $anzahl
                        );
                    }
                } else {
                    $this->dataArray[md5($id)] = array(
                        'id'         => $id,
                        'menge'      => $data
                    );
                }        
            }
        }    
    }

    public function loadProducts($urlParameter = null) {
#        print_r($this->dataArray);
        if(is_array($this->dataArray)) {
            foreach($this->dataArray as $hash => $data) {
                $objElement = $this->Produkt->load($data['id'], $data['attributes']);

                $arrElement['id']            = $data['id'];
                $arrElement['bezeichnung']   = $objElement->bezeichnung;
                $arrElement['produktnummer'] = $objElement->produktnummer;
                $arrElement['attributes']    = $data['attributes'];
                $arrElement['attributelist'] = $objElement->filterArray();
                $arrElement['hash']          = $hash;
                $arrElement['preis']         = $objElement->getPreis($data['menge']);
                $arrElement['menge']         = $data['menge'];
                $arrElement['summe']         = ($arrElement['menge'] * $objElement->getPreis($data['menge']));
                $arrElement['steuer']        = $objElement->steuer;
                if($urlParameter) {
                    $arrElement['url']       = sprintf($urlParameter, $objElement->alias);
                }

                $arrBasket[$hash] = $arrElement;
            }
        }

        return $arrBasket;
    }

    public function add($id, $menge, $attributes = null) {
        if($attributes) {
            $_SESSION['acquistoShop'][$id][$attributes] = $menge;
        } else {
            $_SESSION['acquistoShop'][$id] = $menge;
        }
        
        $this->buildBasket();
    }

    public function remove($hash) {      
        if($this->dataArray[$hash]['attributes']) {
            unset($_SESSION['acquistoShop'][$this->dataArray[$hash]['id']][$this->dataArray[$hash]['attributes']]);
        } else {
            unset($_SESSION['acquistoShop'][$this->dataArray[$hash]['id']]);
        }

        $this->buildBasket();
    }

    public function getWeight() {
        if(is_array($_SESSION['acquistoShop'])) {
            foreach($_SESSION['acquistoShop'] as $id => $data) {
                $Produkt = $this->Produkte->load($id);
                $floatWeight = $floatWeight + ($Produkt->gewicht * $data['menge']);
            }
        }

        return $floatWeight;
    }

    public function getCosts() {
        if(is_array($_SESSION['acquistoShop'])) {
            foreach($this->dataArray as $data) {
                $objProdukt = $this->Produkt->load($data['id'], $data['attributes']);
                
                $floatCosts = $floatCosts + ($objProdukt->getPreis($data['menge']) * $data['menge']);
            }
        }

        return $floatCosts;
    }

    public function itemNum() {
        $arrData = array(
            'summe' => 0,
            'items' => 0
        );

        if(is_array($_SESSION['acquistoShop'])) {
            foreach($this->dataArray as $data) {
                $items  = $items + $data['menge'];
            }

            $arrData = array(
                'summe' => $this->getCosts(),
                'items' => $items
            );
        }

        return $arrData;
    }

    public function getTaxes() {
        if(is_array($this->loadProducts())) {
            foreach($this->loadProducts() as $data) {                        
                $objSteuer  = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && gueltig_ab < NOW() ORDER BY gueltig_ab DESC")->limit(1)->execute($data['steuer']);
                $arrSteuern[$objSteuer->satz ] = $arrSteuern[$objSteuer->satz ] + $data['summe'];
            }

            if(is_array($arrSteuern)) {
                foreach($arrSteuern as $steuersatz => $wert) {
                    $dblSteuer = ($steuersatz + 100) / 100;

                    $arrSteuerberechnung[$steuersatz]['satz']  = $steuersatz;
                    $arrSteuerberechnung[$steuersatz]['wert']  = round($wert - ($wert / $dblSteuer), 2);
                    $arrSteuerberechnung[$steuersatz]['summe'] = $wert;
                }

                $arrSteuern = $arrSteuerberechnung;
            }
        }
        
        return $arrSteuern;
    }

    public function writeOrder($member_id) {
        if(is_array($_SESSION['acquistoShop'])) {
            $this->Database->prepare("INSERT INTO tl_shop_orders (tstamp, member_id, versandzonen_id, zahlungsart_id, versandart_id, gutscheine, payed, versandpreis) VALUES(" . time() . ", " . $member_id . ", " . $arrBasket_Data['shippingMethod'] . ", " . $objZahlungsarten->id . ", " . $arrBasket_Data['paymentMethod'] . ", '" . serialize($this->Gutschein->Checkout($this->User->id)) . "', 0, " . $objVersandart->preis . ");")->execute();
            $objOrder = $this->Database->prepare("SELECT LAST_INSERT_ID() AS lid FROM tl_shop_orders")->execute();

            foreach($_SESSION['acquistoShop'] as $id => $data) {
#                $this->Database->prepare("INSERT INTO tl_shop_orders_items (pid, tstamp, produkt_id, bezeichnung, menge, preis, steuersatz_id) VALUES(" . $objOrder->lid . ", " . time() . ", " . $produkt . ", '" . $arrProdukt['bezeichnung'] . "', '" . $data['menge'] . "', '" . $this->contaoShop->getProductPrice($produkt, $data['menge']) . "', '" . $arrProdukt['steuer'] . "')")->execute();
            }
        }
    }
    
    public function clear() {
        unset($_SESSION['aquistoShop']);
        $this->buildBasket();
    }

    public function __set($name, $value) {
        $this->$name = $value;
//        $_SESSION['aquistoBasket'][$name] = $value;
    }

    public function __get($name) {
        return $this->$name;
    }
}

?>