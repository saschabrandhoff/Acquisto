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
 * Class mod_acquistoShop_filterList
 *
 * Front end module "mod_acquistoShop_filterList".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class mod_acquistoShop_Filterliste extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_produktliste';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO FILTERLISTE ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->Import('acquistoShopProdukt', 'Produkt');
        $this->Import('acquistoShop', 'Shop');
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        /**
         * Versand & Zahlungs Seite
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($GLOBALS['TL_CONFIG']['versandPage']);
        $strVersandpage = $this->generateFrontendUrl($objPage->fetchAssoc(), '');

        /**
         * Weiterleitungsseite ermitteln für Produktanzeige
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        if($this->acquistoShop_zustand) {
            $strSQL .= "zustand = '" . $this->acquistoShop_zustand . "' && ";
        }

        if($this->acquistoShop_hersteller) {
            $strSQL .= "hersteller = '" . $this->acquistoShop_hersteller . "' && ";
        }

        if($this->acquistoShop_produkttype) {
            $strSQL .= "type = '" . $this->acquistoShop_produkttype . "' && ";
        }

        if($this->acquistoShop_markedProducts) {
            $strSQL .= "marked = '" . $this->acquistoShop_markedProducts . "' && ";
        }

        if($this->acquistoShop_warengruppe) {
            $strSQL .= "warengruppen = '" . $this->acquistoShop_warengruppe . "' && ";
        }

        if(is_array($arrTags = explode(",", $this->acquistoShop_tags)) && $this->acquistoShop_tags) {
            foreach($arrTags as $tag) {
                $strSQL .= "tags LIKE '%" . trim($tag) . "%' && ";
            }
        }

        if($strSQL) {
            $strSQL = " WHERE " . $strSQL . "aktiv = 1";
        }

        /**
         * Limit Page ermitteln
         **/
        if(!$this->Input->Get('Page'))
        {
            $this->Input->setGet('Page', 0);
        }

        if((($this->contaoShop_numberOfItems - $this->Input->Get('Page')) < $this->perPage) && $this->contaoShop_numberOfItems) {
            $intShow = ($this->contaoShop_numberOfItems - $this->Input->Get('Page'));
        } elseif($this->contaoShop_numberOfItems && !$this->perPage) {
            $intShow = $this->contaoShop_numberOfItems;
        } else {
            $intShow = $this->perPage;
        }

        /**
         * Produkte auslesen
         */
        $objProdukte = $this->Database->prepare("SELECT * FROM tl_shop_produkte" . $strSQL)->limit($intShow, $this->Input->Get('Page'))->execute();
        $objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_produkte" . $strSQL)->execute($objWarengruppe->id);

        /**
         * Pagniation erstellen
         **/
        $objPageListe = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
        $strUrlListe  = $this->generateFrontendUrl($objPageListe->fetchAssoc(), '/Page/%s');

        if(($this->contaoShop_numberOfItems && $this->perPage) && ($this->contaoShop_numberOfItems - $this->perPage)) {
            $intPages = ceil($this->contaoShop_numberOfItems / $this->perPage);
        } elseif(!$this->contaoShop_numberOfItems && $this->perPage) {
            $intPages = ceil($objTotal->total / $this->perPage);
        } else {
            $intPages = 0;
        }

        if($this->perPage OR $objTotal->total) {
            if(($objTotal->total > ($this->Input->Get('Page') + $this->perPage)) && ($this->Input->Get('Page') < (($intPages - 1) * $this->perPage))) {
                $this->Template->Next = sprintf($strUrlListe, ($this->Input->Get('Page') + $this->perPage));
            }

            if(0 <= ($this->Input->Get('Page') - $this->perPage) && $intPages) {
                $this->Template->Prev = sprintf($strUrlListe, ($this->Input->Get('Page') - $this->perPage));
            }

            if($this->perPage) {
                for($nI = 0; $nI < $intPages; $nI++) {
                    $strClass = null;
                    if($this->Input->Get('Page') == ($nI * $this->perPage))
                    {
                        $strClass = "selected";
                    }

                    $arrPages[] = array(
                        'Page'  => ($nI + 1),
                        'Url'   => sprintf($strUrlListe, ($nI * $this->perPage)),
                        'Class' => $strClass
                    );
                }
            }

            $this->Template->Pages = $arrPages;
        }

        while($objProdukte->next())
        {
            $intCounter++;

            $Produkt                = $this->Produkt->load($objProdukte->id);
            $Produkt->url           = $strUrl;
            $Produkt->default_image = $this->contaoShop_imageSrc;

            if($Produkt->preview_image) {
                $objImage = new stdClass();
                $this->addImageToTemplate($objImage, array (
                        'addImage'    => 1,
                        'singleSRC'   => $Produkt->preview_image,
                        'alt'         => null,
                        'size'        => $this->acquistoShop_galerie_imageSize,
                        'imagemargin' => $this->acquistoShop_galerie_imageMargin,
                        'imageUrl'    => $Produkt->url,
                        'caption'     => null,
                        'floating'    => $this->acquistoShop_galerie_imageFloating,
                        'fullsize'    => $this->acquistoShop_galerie_imageFullsize
                    )
                );

                $Produkt->preview_image = $objImage;
            }

            /**
             * CSS Attribute setzen
             **/

            if($this->acquistoShop_elementsPerRow) {
                if(($intCounter % $this->acquistoShop_elementsPerRow) == 0) {
                    $Produkt->css .= 'break ';
                }
            }

            if($intCounter == 1) {
                $Produkt->css .= 'first ';
            }

            if($intCounter == $objProdukte->numRows) {
                $Produkt->css .= 'last ';
            }

            $arrProdukte[] = $Produkt;
        }

        $Template = new FrontendTemplate(isset($this->acquistoShop_listTemplate) ? 'acquisto_list_default' : $this->acquistoShop_listTemplate);
        $Template->Versand  = $strVersandpage;
        $Template->Produkte = $arrProdukte;

        $this->Template->Produkte = $Template->parse();
    }
}

?>