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

class mod_acquistoShop_Produktdetails extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_produktdetails';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO PRODUKTDETAILS ###';
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
        $this->Import('acquistoShopProdukt', 'Produkt');
        $this->Import('acquistoShopBasket', 'Basket');

        /**
         * Versand & Zahlungs Seite
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($GLOBALS['TL_CONFIG']['versandPage']);
        $this->Template->Versand = $this->generateFrontendUrl($objPage->fetchAssoc(), '');

        if($this->Input->Post('action') == 'add2basket') {
            $this->Basket->add($this->Input->Post('id'), $this->Input->Post('menge'), $this->Input->Post('attributes'));
            $this->Input->setPost('action', null);
        }

        if($this->contaoShop_socialFacebook) {
            $this->Template->Facebook = urlencode('http://' . $this->Environment->httpHost  . $this->Environment->requestUri);
        }

        if($this->contaoShop_socialTwitter) {
            $this->Template->Twitter = urlencode('http://' . $this->Environment->httpHost  . $this->Environment->requestUri);
        }

        $objProdukt = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE alias=?")->execute($this->Input->Get('produkt'));
        $objProdukt = $this->Produkt->load($objProdukt->id, str_replace("\"", "'", serialize($this->Input->Post('attribute'))));

        $this->pageTitle($objProdukt->bezeichnung);

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
                    'size'        => $this->contaoShop_imageSize,
                    'imagemargin' => $this->contaoShop_imageMargin,
                    'imageUrl'    => $objProdukt->url,
                    'caption'     => null,
                    'floating'    => $this->contaoShop_imageFloating,
                    'fullsize'    => $this->contaoShop_imageFullsize
                )
            , null, $objProdukt->id);

            $objProdukt->preview_image = $objImage;
        }

        /**
         * Prüfen ob Galerie angelegt werden muss
         **/
        if($objProdukt->galerie) {
            $arrGalerie = deserialize($objProdukt->galerie);
            if(is_array($arrGalerie)) {
                foreach($arrGalerie as $value) {
                    $objGalerie = new stdClass();
                    $this->addImageToTemplate($objGalerie, array
                        (
                            'addImage'    => 1,
                            'singleSRC'   => $value,
                            'alt'         => null,
                            'size'        => $this->acquistoShop_galerie_imageSize,
                            'imagemargin' => $this->acquistoShop_galerie_imageMargin,
                            'imageUrl'    => $objProdukt->url,
                            'caption'     => null,
                            'floating'    => $this->acquistoShop_galerie_imageFloating,
                            'fullsize'    => $this->acquistoShop_galerie_imageFullsize
                        )
                    , null, $objProdukt->id);

                    $arrGalerieItems[] = $objGalerie;
                }


                $objProdukt->galerie = $arrGalerieItems;
            }
        }


        /**
         * Comments
         **/
        /**
        global $objPage;

        $this->import('Comments');
        $objConfig = new stdClass();

        $objConfig->perPage = $this->perPage;
        $objConfig->order = $this->com_order;
        $objConfig->template = $this->com_template;
        $objConfig->requireLogin = $this->com_requireLogin;
        $objConfig->disableCaptcha = $this->com_disableCaptcha;
        $objConfig->bbcode = $this->com_bbcode;
        $objConfig->moderate = $this->com_moderate;

        $this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_shop_produkte', $objPage->id, $GLOBALS['TL_ADMIN_EMAIL']);
        **/

        $this->Template->Produkt = $objProdukt;
    }

    protected function pageTitle($strTitle)
    {
        global $objPage;
        $objPage->title = $strTitle;
        return;
    }
}

?>