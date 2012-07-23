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
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class mod_acquistoShop_Gutschein extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_gutschein';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO GUTSCHEIN ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if($this->Input->Get('do'))
        {
            return '';
        }

        if (FE_USER_LOGGED_IN) {
            $this->Import('FrontendUser', 'User');
        }

        $this->Import('acquistoShopGutschein', 'Gutschein');
        $this->Import('acquistoShop', 'Shop');
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if (FE_USER_LOGGED_IN) {
            $objGutscheine = $this->Database->prepare("SELECT id FROM tl_shop_gutscheine WHERE kunden_id = ?")->execute($this->User->id);

            while($objGutscheine->next()) {
                $arrGutscheine[] = $this->Gutschein->getGutschein($objGutscheine->id);
            }

            $this->Template->Gutscheine = $arrGutscheine     ;
        }

        $this->Template->UseList = $this->Gutschein->getList();

        if($this->Input->Post('FORM_SUBMIT') == 'tl_acquistoShop_gutschein') {
            $objGutschein = $this->Database->prepare("SELECT * FROM tl_shop_gutscheine WHERE code = ?")->limit(1)->execute($this->Input->Post('code'));

            if($this->Gutschein->validateGutschein($objGutschein->id, $this->User->id)) {
                $this->Gutschein->addGutschein2Use($objGutschein->id);

                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
                $this->redirect($this->generateFrontendUrl($objPage->fetchAssoc()));
            }
        }
    }

}

?>