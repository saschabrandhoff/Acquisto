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

class mod_acquistoShop_Versand extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_versand';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO ZAHLUNG &amp; VERSAND ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        $arrCountries = $this->getCountries();

        if($GLOBALS['TL_CONFIG']['versandberechnung'] == "gewicht") {
            $this->Template->Symbol = "kg";
        } else {
            $this->Template->Symbol = "EUR";
        }

        $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen;")->execute();
        while($objVersandzonen->next()) {
            $objVersandarten = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE pid = ? ORDER BY pid, zahlungsart_id")->execute($objVersandzonen->id);
            $strCountrys = null;

            if($objVersandzonen->laender) {
                $arrLaender = unserialize($objVersandzonen->laender);
                foreach($arrLaender as $value) {
                    $strCountrys .= $arrCountries[$value] . ", ";
                }
            }

            $arrZahlungsart = null;
            while($objVersandarten->next()) {
                $objZahlungsart = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($objVersandarten->zahlungsart_id);
                $arrZahlungsart[$objZahlungsart->id]['bezeichnung'] = $objZahlungsart->bezeichnung;
                $arrZahlungsart[$objZahlungsart->id]['infotext']    = $objZahlungsart->infotext;
                $arrZahlungsart[$objZahlungsart->id]['versandkosten'][] = array(
                    'ab_einkaufspreis' => sprintf("%01.2f", $objVersandarten->ab_einkaufpreis),
                    'preis'            => sprintf("%01.2f", $objVersandarten->preis)

                );
            }

            $arrVersandzonen[$objVersandzonen->id]['bezeichnung']   = $objVersandzonen->bezeichnung;
            $arrVersandzonen[$objVersandzonen->id]['laender']       = substr($strCountrys, 0, -2);
            $arrVersandzonen[$objVersandzonen->id]['zahlungsarten'] = $arrZahlungsart;
        }

        $this->Template->Versandkosten = $arrVersandzonen;
    }
}

?>