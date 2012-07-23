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

class mod_acquistoShop_Warengruppen extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_warengruppen';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO WARENGRUPPEN ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if(FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        $this->Import('acquistoShopNavigation', 'acquistoShopNavigationer');

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/warengruppe/%s');
        if($this->Input->Get('warengruppe') OR $this->Input->Get('produkt'))
        {
            if($this->Input->Get('warengruppe'))
            {
                 $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias=?")->limit(1)->execute($this->Input->Get('warengruppe'));
                $arrWarengruppen = $this->renderWarengruppen1($objWarengruppe->id, $this->contaoShop_levelOffset);
            }
            else
            {
                $objProdukt = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE alias=?")->limit(1)->execute($this->Input->Get('produkt'));
                $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")->limit(1)->execute($pbjProdukt->id);
                $arrWarengruppen = $this->renderWarengruppen1($objWarengruppe->pid, $this->contaoShop_levelOffset);
            }
        }
        else
        {
            $arrWarengruppen = $this->renderWarengruppen1(0, $this->contaoShop_levelOffset);
        }

        $this->Template->items = $arrWarengruppen;
    }

    public function renderWarengruppen1($pid, $level=0)
    {
        if($level) {
            $start = $this->acquistoShopNavigationer->shopTrail[($level-1)];
        } else {
            $start = 0;
        }

        $objTemplate = new FrontendTemplate('warengruppen_default');

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/warengruppe/%s');

        $objWarengruppen = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE pid=? && published=1")->execute($start);
        while($objWarengruppen->next()) {
            $arrElement = $objWarengruppen->row();
            $arrSubItems = null;
            $objSubpages = null;

            // Prüfen ob die Warengruppe nur bestimmten Membergroups zur Verfügung steht
            $bRun = false;
            if(!$objWarengruppen->member_groups) {
                $bRun = true;
            } elseif(FE_USER_LOGGED_IN) {
                $arrGroups = unserialize($objWarengruppen->member_groups);
                foreach($this->User->groups as $group) {
                    if(in_array($group, $arrGroups)) {
                        $bRun = true;
                    }
                }
            }

            if($bRun) {
                if($level < $this->contaoShop_showLevel && $arrElement['id'] == $this->acquistoShopNavigationer->shopTrail[($level)]) {
                    $objSubpages = $this->Database->prepare("SELECT COUNT(*) AS totalC FROM tl_shop_warengruppen WHERE pid=?")->execute($objWarengruppen->id);

                    if($objSubpages->totalC)
                    {
                        $arrSubItems = $this->renderWarengruppen1($objWarengruppen->id, ($level + 1));
                    }
                }

                $arrElement['subitems'] = $arrSubItems;

                $strSprint = $strUrl;
                if($arrElement['type'] == "redirect") {
                    $objRedirect = $this->Database->prepare("SELECT alias FROM tl_shop_warengruppen WHERE id = ?")->limit(1)->execute($arrElement['jumpTo']);
                    $arrElement['alias'] = $objRedirect->alias;
                } elseif($arrElement['type'] == "page") {
                    $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($arrElement['jumpToPage']);
                    $arrElement['alias'] = $objPage->alias;
                    $strSprint = $this->generateFrontendUrl($objPage->fetchAssoc(), '%s');
                }

                $arrElement['url']      = sprintf($strSprint, $arrElement['alias']);

                if($arrElement['id'] == $this->acquistoShopNavigation->shopTrail[($level)]) {
                    $arrElement['class'] = "trail";
                }

                if($this->Input->Get('warengruppe') == $arrElement['alias'])
                {
                    $arrElement['isActive'] = 1;
                }

                $arrItems[] = $arrElement;
            }
        }


        if (count($arrItems))
        {
            $last = count($arrItems) - 1;

            $arrItems[0]['class'] = trim($arrItems[0]['class'] . ' first');
            $arrItems[$last]['class'] = trim($arrItems[$last]['class'] . ' last');
        }

        $objTemplate->level = "level_" . $level;
        $objTemplate->items = $arrItems;
        return count($arrItems) ? $objTemplate->parse() : '';
    }
}

?>