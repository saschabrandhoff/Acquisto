<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Table tl_cds
 */
$GLOBALS['TL_DCA']['tl_shop_produkte'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_produkte_attribute'),
        'enableVersioning'            => true
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('bezeichnung'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
,           'label_callback'          => array('tl_shop_produkte', 'listProdukte')
        ),
        'label' => array
        (
            'fields'                  => array('bezeichnung', 'produktnummer', 'type'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s / %s]</span>'
        ),
        'global_operations' => array
        (
            'importData' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['importData'],
                'href'                => 'key=importData',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
                'button_callback'     => array('tl_shop_produkte', 'changeButton')
            ),
//            'attributes' => array
//            (
//                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['attr'],
//                'href'                => 'table=tl_shop_produkte_attribute',
//                'icon'                => 'system/modules/contaoShop/html/icons/book_link.png'
//            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type'),
        'default'                     => '{title},type,produktnummer,bezeichnung,alias;{extended_options},steuer,zustand,hersteller,gewicht,tags,teaser,beschreibung;{grundpreis},mengeneinheit,inhalt,berechnungsmenge;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{state},marked,aktiv;',
        'normal'                      => '{title},type,produktnummer,bezeichnung,alias;{extended_options},steuer,zustand,hersteller,gewicht,tags,teaser,beschreibung;{grundpreis},mengeneinheit,inhalt,berechnungsmenge;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{state},marked,aktiv;',
        'digital'                     => '{title},type,produktnummer,bezeichnung,alias;{extended_options},steuer,zustand,hersteller,tags,teaser,beschreibung;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{digital},digital_product;{state},marked,aktiv;',
        'variant'                     => '{title},type,produktnummer,bezeichnung,alias;{extended_options},steuer,zustand,hersteller,tags,teaser,beschreibung;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{state},marked,aktiv;',
    ),


    // Fields
    'fields' => array
    (
        'type' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['type'],
            'inputType'               => 'select',
            'exclude'                 => true,
            'search'                  => true,
            'options'                 => array('normal'=>'Normales Produkt', 'digital'=>'Digitales Produkt', 'variant'=> 'Varianten Produkt'),
            'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['bezeichnung'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
        ),
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['alias'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('tl_shop_produkte', 'generateAlias')
            )
        ),
        'produktnummer' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['produktnummer'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'gewicht' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['gewicht'],
            'inputType'               => 'text',
            'default'                 => 0,
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit', 'tl_class'=>'w50')
        ),
        'zustand' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['zustand'],
            'inputType'               => 'select',
            'options'                 => array('new' => 'Neu', 'used'=>'Gebraucht', 'refurbished' => 'Erneuert'),
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50', 'includeBlankOption'=>true)
        ),
        'steuer' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['steuer'],
            'default'                 => $GLOBALS['TL_CONFIG']['steuer'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_steuer.bezeichnung',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'mengeneinheit' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['mengeneinheit'],
            'default'                 => $GLOBALS['TL_CONFIG']['steuer'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_mengeneinheit.bezeichnung',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true)
        ),
        'inhalt' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['inhalt'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'rgxp'=> 'digit ', 'maxlength'=>10, 'tl_class'=>'w50')
        ),
        'berechnungsmenge' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['berechnungsmenge'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'rgxp'=> 'digit ', 'maxlength'=>10, 'tl_class'=>'w50')
        ),
        'hersteller' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['hersteller'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_hersteller.bezeichnung',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50', 'includeBlankOption'=>true)
        ),
        'teaser' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['teaser'],
            'inputType'               => 'textarea',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('style'=>'height: 60px;', 'mandatory'=>false, 'tl_class'=>'clr')
        ),
        'tags' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['tags'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'long  clr')
        ),
        'beschreibung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['beschreibung'],
            'inputType'               => 'textarea',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'clr')
        ),
        'preise' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise'],
            'inputType'               => 'preisWizard',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false)
        ),
        'warengruppen' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['warengruppen'],
            'inputType'               => 'categorieTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'radio')
        ),
        'preview_image' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preview_image'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif')
        ),
        'galerie' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['galerie'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif')
        ),
        'digital_product' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['digital_product'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'checkbox', 'files'=>true,'filesOnly'=>true,'extensions'=>strtolower($GLOBALS['TL_CONFIG']['allowedDownload']))
        ),
        'marked' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['marked'],
            'inputType'               => 'checkbox',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'m12 w50')
        ),
        'aktiv' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['aktiv'],
            'inputType'               => 'checkbox',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'m12 w50')
        )
    )
);

/**
 * Class tl_style
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_shop_produkte extends Backend
{

    /**
     * Add the mooRainbow scripts to the page
     */
    public function __construct()
    {
        parent::__construct();

        $GLOBALS['TL_CSS'][] = 'plugins/mootools/rainbow.css?'. MOO_RAINBOW . '|screen';
        $GLOBALS['TL_JAVASCRIPT'][] = 'plugins/mootools/rainbow.js?' . MOO_RAINBOW;

        $this->import('BackendUser', 'User');
    }

    public function listProdukte($arrRow) {
        return 1;
    }

    public function changeButton($row, $href, $label, $title, $icon, $attributes)
    {
        if($row['type'] == 'variant') {
            return '<a title="edit" href="' . $this->addToUrl(str_replace("act=edit", "table=tl_shop_produkte_attribute", $href)) . "&id=" . $row['id'] . '"><img width="12" height="16" alt="' . $label . '" title="' . $title . '" src="system/themes/default/images/' . $icon . '"></a>&nbsp;';
        } else {
            return '<a title="edit" href="' . $this->addToUrl($href) . "&id=" . $row['id'] . '"><img width="12" height="16" alt="' . $label . '" title="' . $title . '" src="system/themes/default/images/' . $icon . '"></a>&nbsp;';
        }
    }

    /**
     * Autogenerate a page alias if it has not been set yet
     * @param mixed
     * @param object
     * @return string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if (!strlen($varValue))
        {
            $autoAlias = true;
            $varValue = standardize($dc->activeRecord->bezeichnung);
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE id=? OR alias=?")
                                   ->execute($dc->id, $varValue);

        // Check whether the page alias exists
        /*
        if ($objAlias->numRows > 1)
        {
            $arrDomains = array();

            while ($objAlias->next())
            {
                $_pid = $objAlias->id;
                $_type = '';

                do
                {
                    $objParentPage = $this->Database->prepare("SELECT id, pid, alias, type FROM tl_shop_produkte WHERE id=?")
                                                    ->limit(1)
                                                    ->execute($_pid);

                    if ($objParentPage->numRows < 1)
                    {
                        break;
                    }

                    $_pid = $objParentPage->pid;
                    $_type = $objParentPage->type;
                }
                while ($_pid > 0 && $_type != 'normal');

                $arrDomains[] = ($objParentPage->numRows && ($objParentPage->type == 'normal' || $objParentPage->pid > 0)) ? $objParentPage->dns : '';
            }

            $arrUnique = array_unique($arrDomains);

            if (count($arrDomains) != count($arrUnique))
            {
                if (!$autoAlias)
                {
                    throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
                }

                $varValue .= '-' . $dc->id;
            }
        }
        */
        return $varValue;
    }
}

?>