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
 * Class mod_acquistoShop_Recent
 *
 * Front end module "mod_acquistoShop_Recent".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class mod_acquistoShop_Recent extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_recent';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO LETZTE PRODUKTE ###';
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
        $this->Import('acquistoShop', 'Shop');

        /**
         * Weiterleitungsseite ermitteln für Produktanzeige
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        if($this->Input->Get('produkt')) {
            $objSelected = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE alias = ?")->limit(1)->execute($this->Input->Get('produkt'));
            $arrRecently[] = $objSelected->id;
        }

        if(is_array($_SESSION['acquistoRescent'])) {
            foreach($_SESSION['acquistoRescent'] as $produkt_id) {
                $objProdukt = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE id = ?")->limit(1)->execute($produkt_id);
                $intCounter++;

                if($this->contaoShop_numberOfItems >= $intCounter) {

                    /**
                     * Bildeinstellungen
                     **/

                    if($objProdukt->preview_image OR $this->contaoShop_imageSrc)
                    {
                        if($objProdukt->preview_image)
                        {
                            $strImage = $objProdukt->preview_image;
                        }
                        else
                        {
                            $strImage = $this->contaoShop_imageSrc;
                        }

                        $objImage = new stdClass();
                        $this->addImageToTemplate($objImage, array
                            (
                                'addImage'    => 1,
                                'singleSRC'   => $strImage,
                                'alt'         => null,
                                'size'        => $this->acquistoShop_galerie_imageSize,
                                'imagemargin' => $this->acquistoShop_galerie_imageMargin,
                                'imageUrl'    => sprintf($strUrl, $objProdukt->alias),
                                'caption'     => null,
                                'floating'    => $this->acquistoShop_galerie_imageFloating,
                                'fullsize'    => $this->acquistoShop_galerie_imageFullsize
                            )
                        );
                    } else {
                        $objImage = null;
                    }

                    /**
                     * CSS Elemente
                     **/

                    $strCss = isset($objProdukt->zustand) ? $objProdukt->zustand . ' ' : null;

                    if($intCounter == 1) {
                        $strCss .= 'first ';
                    }

                    if($intCounter == $this->contaoShop_numberOfItems) {
                        $strCss .= 'last ';
                    }

                    if($objProdukt->marked) {
                        $strCss .= 'marked ';
                    }


                    $arrCurrent[] = array(
                        'bezeichnung'   => $objProdukt->bezeichnung,
                        'url'           => sprintf($strUrl, $objProdukt->alias),
                        'preis'         => $this->Shop->getProductPrice($objProdukt->id, 0),
                        'grundpreis'    => $this->Shop->getProductGrundpreis($objProdukt->id, 0),
                        'css'           => $strCss,
                        'preview_image' => $objImage
                    );
                }

                if($objSelected->id != $produkt_id) {
                    $arrRecently[] = $objProdukt->id;
                }

            }
        }


        $_SESSION['acquistoRescent'] = $arrRecently;
        $this->Template->Viewed = $arrCurrent;
    }

}

?>