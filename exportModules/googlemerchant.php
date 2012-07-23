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

class googlemerchant extends Controller {
    protected $exportModule = 'Google Shopping';
    public $exportID;

    public function __construct()
    {
#        parent::__construct();
    }

    public function getExportInfo() {
        return $this->exportModule;
    }

    public function getSettingsData() {
        $arrConfig = array(
            'Seitentitel' => array
            (
                'label'                   => array('Seitentitel'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'Beschreibung' => array
            (
                'label'                   => array('Beschreibung'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
            )
        );

        return $arrConfig;
    }

    public function compile()
    {
        $this->Import('Database');
        $this->Import('Environment');
        $this->Import('acquistoShop', 'Shop');

        $objExport = $this->Database->prepare("SELECT * FROM tl_shop_export WHERE id = ?")->limit(1)->execute($this->exportID);
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objExport->produktDetails);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        $arrSettings = unserialize($objExport->exportSettings);

        $htmlData .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $htmlData .= "<rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\">\n";
        $htmlData .= "    <channel>\n";
        $htmlData .= "        <title>" . $arrSettings['Seitentitel'] . "</title>\n";
        $htmlData .= "        <description>" . $arrSettings['Beschreibung'] . "</description>\n";
        $htmlData .= "        <link>http://" . $this->Environment->httpHost . "</link>\n";

        $objProdukte = $this->Database->prepare("SELECT * FROM tl_shop_produkte;")->execute();
        while($objProdukte->next()) {

            $htmlData .= "        <item>\n";
            $htmlData .= "            <title><![CDATA[" . $objProdukte->bezeichnung . "]]></title>\n";
            $htmlData .= "            <link>http://" . $this->Environment->httpHost . "/" . sprintf($strUrl, $objProdukte->alias) . "</link>\n";
            $htmlData .= "            <description><![CDATA[" . $objProdukte->teaser . "]]></description>\n";
            $htmlData .= "            <g:id>" . $objProdukte->id . $objProdukte->produktnummer . "</g:id>\n";
            $htmlData .= "            <g:condition>" . $objProdukt->zustand . "</g:condition>\n";
            $htmlData .= "            <g:preis>" . str_replace(".", ",", $this->Shop->getProductPrice($objProdukte->id, 0)) . " EUR</g:preis>\n";
            $htmlData .= "            <g:availability>in stock</g:availability>\n";

            if($objProdukte->preview_image) {
                $htmlData .= "            <g:image_link>http://" . $this->Environment->httpHost . "/" . $objProdukte->preview_image . "</g:image_link>\n";
            }

            $htmlData .= "        </item>\n";
//<g:shipping>
//   <g:country>DE</g:country>
//   <g:service>Standard</g:service>
//   <g:price>5,95</g:price>
//</g:shipping>
        }

        $htmlData .= "    </channel>\n";
        $htmlData .= "</rss>\n";

        return $htmlData;
    }
}


?>