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
 * Class mod_acquistoShop_Produktfilter
 *
 * Front end module "mod_acquistoShop_Produktfilter".
 * @version    1.2 - 20120704034801
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class mod_acquistoShop_Produktfilter extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_produktfilter';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO PRODUKTFILTER ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if(!$this->Input->Get('warengruppe')) {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        global $objPage;

        $this->Import('acquistoShopProdukt', 'Produkt');

        $objWarengruppePage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objPage->id);
        $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias = ?")->limit(1)->execute($this->Input->Get('warengruppe'));

        $strUrl = '/warengruppe/' . $objWarengruppe->alias;
        $strUrl = $this->generateFrontendUrl($objWarengruppePage->fetchAssoc(), $strUrl . '/%s/%s');

        $objProdukte = $this->Database->prepare("SELECT id,hersteller FROM tl_shop_produkte WHERE warengruppen = ? && aktiv = 1 GROUP BY hersteller")->execute($objWarengruppe->id);

        while($objProdukte->next()) {
            $objHersteller = $this->Database->prepare("SELECT id, bezeichnung FROM tl_shop_hersteller WHERE id = ?")->limit(1)->execute($objProdukte->hersteller);

            if($objHersteller->id) {
                $objCounter = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_produkte WHERE warengruppen = ? && hersteller = ? && aktiv = 1")->execute($objWarengruppe->id, $objHersteller->id);

                $objObjekt = new stdClass();
                $objObjekt->id          = $objHersteller->id;
                $objObjekt->bezeichnung = $objHersteller->bezeichnung;
                $objObjekt->url         = sprintf($strUrl, 'hersteller', $objHersteller->id);
                $objObjekt->counter     = $objCounter->total;

                if($this->Input->Get('hersteller') == $objHersteller->id) {
                    $objObjekt->css = 'active';
                    $intActive = true;
                }

                $arrHersteller[] = $objObjekt;

            }
        }

        $objProdukte = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE warengruppen = ? && aktiv = 1")->execute($objWarengruppe->id);

        while($objProdukte->next()) {
            $Produkt = new Produkt($objProdukte->id);
            $arrProdukte[] = $Produkt;
            $arrPreise[]   = $Produkt->getPreis(0);
        }

        if(is_array($arrPreise)) {
            asort($arrPreise);
            for($nI = 0; $nI < end($arrPreise); $nI = $nI * 10) {
                if(!$nI) {
                    $nI = 1;
                }
            }

            $intTeiler = $nI / 10;
            $intSteps = round(end($arrPreise) / $intTeiler, 0) + 1;

            for($nI = 0; $nI < $intSteps; $nI++) {
                $intCounter = null;
                if(is_array($arrProdukte)) {
                    foreach($arrProdukte as $Produkt) {
                        if(($Produkt->getPreis(0) >= ($nI * $intTeiler)) && ($Produkt->getPreis(0) < (($nI + 1) * $intTeiler))) {
                            $intCounter++;
                        }
                    }
                }

                if($intCounter) {
                    $strCSS = null;
                    if($this->Input->Get('preis') == ($nI * $intTeiler) . "-" . (($nI + 1) * $intTeiler)) {
                        $strCSS = 'active';
                        $intActive = true;
                    }

                    $arrPreisStruktur[] = (object) array(
                        'von'     => $nI * $intTeiler,
                        'bis'     => ($nI + 1) * $intTeiler,
                        'url'     => sprintf($strUrl, 'preis', ($nI * $intTeiler) . "-" . (($nI + 1) * $intTeiler)),
                        'css'     => $strCSS,
                        'counter' => $intCounter
                    );
                }
            }
        }

        if($intActive) {
            $objDefault = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objPage->id);
            $this->Template->Clear = sprintf($this->generateFrontendUrl($objDefault->fetchAssoc(), '/warengruppe/%s'), $objWarengruppe->alias);
        }

        $boolFilter = false;
        // Prüfen ob es Preisfiltermöglichenkeiten gibt
        if(is_array($arrPreisStruktur)) {
            if(count($arrPreisStruktur) > 1) {
                $this->Template->Preise = $arrPreisStruktur;
                $boolFilter = true;
            }
        }

        // Prüfen ob es Herstellerfiltermöglichenkeiten gibt
        if(is_array($arrHersteller)) {
            if(count($arrHersteller) > 1) {
                $this->Template->Hersteller = $arrHersteller;
                $boolFilter = true;
            }
        }

        // Wenn keiner Filter verfügbar sind Template ausblenden
        if($boolFilter == false) {
            $this->Template = new FrontendTemplate();
        }
    }

}

?>