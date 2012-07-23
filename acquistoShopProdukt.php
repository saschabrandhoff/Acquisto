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

class acquistoShopProdukt extends Controller {
    public function __construct() {

        if (isset($GLOBALS['TL_HOOKS']['modifyProduct']) && is_array($GLOBALS['TL_HOOKS']['modifyProduct'])) {
            foreach ($GLOBALS['TL_HOOKS']['modifyProduct'] as $callback) {
                $this->import($callback[0]);
            }
        }
    }


    public function load($id, $attributes = null) {
        $objProdukt = new Produkt($id, $attributes);

        if (isset($GLOBALS['TL_HOOKS']['modifyProduct']) && is_array($GLOBALS['TL_HOOKS']['modifyProduct'])) {
            foreach ($GLOBALS['TL_HOOKS']['modifyProduct'] as $callback) {
                $objProdukt = $this->$callback[0]->$callback[1]($objProdukt);
            }
        }

        return $objProdukt;
    }
}

class Produkt extends Controller
{
    var $produkt_id       = 0;
    var $produktArray     = array();
    var $default_image    = '';
    var $image_properties = array();
    private $attribute    = null;

    public function __construct($id, $attributes = null)
    {
        parent::__construct();
        $this->Import('Database');

        $this->produkt_id = $id;
        $this->produkt_attributes = unserialize(str_replace("'", "\"", $attributes));
        $this->loadProdukt();
    }

    public function __set($name, $value) {
        switch($name) {
            case "url":
                $this->formatUrl($value);
                break;;
            case "default_image":
                if(!$this->preview_image) {
                    $this->preview_image = $value;
                }
                break;;
            default:
                $this->$name = $value;
                break;;
        }
    }

    public function __get($name) {
        return $this->$name;
    }

    public function loadProdukt()
    {
        $objLoad = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE id = ?")->limit(1)->execute($this->produkt_id);
        $arrLoad = $objLoad->row();

        if(is_array($arrLoad)) {
            foreach($arrLoad as $key => $value) {
                $this->__set($key, $value);
            }
        }

        $this->buildCss();

        switch($this->type) {
            case 'normal':
                $this->sortPreise();
                break;;
            case 'digital':
                $this->sortPreise();
                $this->buildDigital();
                break;;
            case 'variant':
                $this->varianten = $this->buildVariant($this->produkt_id, 0, $this->produkt_attributes);
                $this->attribute = str_replace("\"", "'", serialize($this->produkt_attributes));
                $this->sortPreise();
                break;;
        }
    }

    private function formatUrl($url) {
        $this->url = sprintf($url, $this->alias);
    }

    private function buildVariant($pid, $pattribut_id = 0, $filter = null) {
        $objProdukt_Attrib = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE pid=? && pattribut_id=?;")->execute($pid, $pattribut_id);
        $objAttribute      = $this->Database->prepare("SELECT * FROM tl_shop_attribute WHERE id=?")->limit(1)->execute($objProdukt_Attrib->attribut_id);
        $objBefore         = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE id=?;")->execute($objProdukt_Attrib->id);
        $objVarianten      = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE (pid=? && produkt_attribut_id=0) OR (pid=? && produkt_attribut_id=?)")->execute($objProdukt_Attrib->id, $objProdukt_Attrib->id, $filter[$objBefore->pattribut_id]);

        while($objVarianten->next()) {
            $objItem = (object) $objVarianten->row();

            if(isset($filter[$objProdukt_Attrib->id])) {
                if($filter[$objProdukt_Attrib->id] == $objVarianten->id) {
                    $objItem->selected = true;
                    $arrPreise = unserialize($objVarianten->preise);
                    $filter[$objProdukt_Attrib->id] = $objItem->id;
                }
            } else {
                if($boolSet == false) {
                    $boolSet = true;
                    $objItem->selected = true;
                    $arrPreise = unserialize($objVarianten->preise);
                    $filter[$objProdukt_Attrib->id] = $objItem->id;
                 }
            }

            $arrVarianten[] = $objItem;
        }

        // Preise nachbauen
        if(is_array($arrPreise)) {
            foreach($arrPreise as $value) {
                if($value['label']) {
                    $this->preise = serialize($arrPreise);
                }
            }
        }

        // Building Template
        if($objAttribute->feldtyp && count($arrVarianten)) {
            $objTemplate = new FrontendTemplate('variant_' . $objAttribute->feldtyp);
            $objTemplate->ID = $objProdukt_Attrib->id;
            $objTemplate->Bezeichnung = $objAttribute->bezeichnung;
            $objTemplate->Options = $arrVarianten;

            $html = $objTemplate->parse();
        }

        $this->produkt_attributes = $filter;

        // Weitere Attribute und Varianten abholen
        $objSummary = $this->Database->prepare("SELECT COUNT(*) AS Summary FROM tl_shop_produkte_attribute WHERE pid=? && pattribut_id=?;")->execute($pid, $pattribut_id);
        if($objSummary->Summary) {
            $html .= $this->buildVariant($pid, $objProdukt_Attrib->id, $filter);
        }

        return $html;
    }

    private function buildDigital() {
        if(is_array($arrDownload = deserialize($this->digital_product))) {
            $arrDigitalDownloads = array();

            foreach($arrDownload as $download) {
                if(is_file(TL_ROOT . '/' . $download) && !in_array($download, $arrDigitalDownloads)) {
                    $arrDigitalDownloads[] = $download;
                }
            }
        }

        $this->digital_product = $arrDigitalDownloads;
    }

    private function buildCss() {
        $this->css = isset($this->produkt->zustand) ? $this->zustand . ' ' : null;
        if($this->marked) {
            $this->css .= 'marked ';
        }
    }

    public function getPreis($menge) {
        if(is_array($arrPreise = $this->preise)) {
            if(count($arrPreise) == 1) {
                $endpreis = $arrPreise[0]->preis;
            } else {
                foreach($arrPreise as $value) {
                    if($menge >= $value->menge) {
                        $endpreis = $value->preis;
                    }
                }
            }
        }

        return $endpreis;
    }

    private function sortPreise() {
        $preise = unserialize($this->preise);
        if(is_array($preise)) {
            foreach($preise as $value) {
                $arrSort[$value['value']] = $value['label'];
            }

            arsort($arrSort);

            foreach($arrSort as $menge => $preis) {
                $arrPreise[] = (object) array (
                    'menge'      => $menge,
                    'preis'      => $preis
                );
            }
        } else {
            $arrPreise[] = (object) array (
                'menge'      => 0,
                'preis'      => 0
            );
        }

        $this->preise = $arrPreise;
    }
    
    public function filterArray() {
        if(is_array($this->produkt_attributes)) {
            foreach($this->produkt_attributes as $attribute => $selection) {
                $objAttribute = $this->Database->prepare("SELECT tl_shop_attribute.* FROM tl_shop_attribute, tl_shop_produkte_attribute WHERE tl_shop_produkte_attribute.id=? && tl_shop_produkte_attribute.attribut_id = tl_shop_attribute.id")->execute($attribute);
                $objVariante  = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE id=?")->execute($selection);
                $arrAttributes[] = (object) array(
                    'title'     => $objAttribute->bezeichnung,
                    'selection' => $objVariante->bezeichnung
                );
            }            
        }
        
        return $arrAttributes;
    }
}

?>