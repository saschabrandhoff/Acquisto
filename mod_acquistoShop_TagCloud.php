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

class mod_acquistoShop_TagCloud extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_tagcloud';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO TAG-CLOUD ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $objSummary = $this->Database->prepare("SELECT COUNT(*) AS totalCount FROM tl_shop_produkte WHERE tags!=''")->execute();
        if(!$objSummary->totalCount) {
            return '';
        }
        
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        /**
         * Weiterleitungsseite ermitteln für Sucheergbniss
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/tag/%s');

        $objProdukte = $this->Database->prepare("SELECT tags FROM tl_shop_produkte WHERE tags != '' && aktiv = 1")->execute();

        /**
         * Pagniation erstellen
         **/

        while($objProdukte->next())
        {
            $arrTags = explode(",", $objProdukte->tags);
            if(is_array($arrTags)) {
                foreach($arrTags as $item) {
                    $arrCloud[trim($item)]++;
                }
            }
        }

        if(is_array($arrCloud)) {
            foreach($arrCloud as $item) {
                if($max < $item) {
                    $max = $item;
                }
            }

            foreach($arrCloud as $item) {
                if($max > $item) {
                    $min = $item;
                }
            }

            foreach($arrCloud as $key => $item) {
                $arrBuild[] = array(
                    'prozent' => round((((100 / $max) * $item) + 50), 0),
                    'num'     => $item,
                    'tag'     => $key,
                    'url'     => sprintf($strUrl, urlencode($key))
                );
            }
        }

        $this->Template->Cloud = $arrBuild;
    }
}

?>