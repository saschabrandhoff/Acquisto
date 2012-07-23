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
$GLOBALS['TL_DCA']['tl_shop_versandzonen_varten'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_versandzonen',
        'enableVersioning'            => true
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('zahlungsart_id', 'ab_einkaufpreis'),
            'headerFields'            => array('bezeichnung', 'laender'),
            'flag'                    => 12,
            'panelLayout'             => 'search,limit',
            'disableGrouping'         => true,
            'child_record_callback'   => array('tl_shop_versandzonen_varten', 'listItems'),
        ),
        'label' => array
        (
            'fields'                  => array('bezeichnung'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[]</span>'
        ),
        'global_operations' => array
        (
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},steuersatz_id,zahlungsart_id,ab_einkaufpreis,preis;',
    ),


    // Fields
    'fields' => array
    (
        'steuersatz_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['steuersatz_id'],
            'inputType'               => 'select',
            'search'                  => true,
            'foreignKey'              => 'tl_shop_steuer.bezeichnung',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'includeBlankOption'=>true)
        ),
        'zahlungsart_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['zahlungsart_id'],
            'inputType'               => 'select',
            'search'                  => true,
            'foreignKey'              => 'tl_shop_zahlungsarten.bezeichnung',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'includeBlankOption'=>true)
        ),
        'ab_einkaufpreis' => array
        (
            'label'                   => array('ab ' . ucfirst($GLOBALS['TL_CONFIG']['versandberechnung']), ''),
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'preis' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen_varten']['preis'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        )
    )
);

class tl_shop_versandzonen_varten extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listItems($arrRow)
    {
        if($GLOBALS['TL_CONFIG']['versandberechnung'] == "gewicht") {
            $strSymbol = "kg";
        } else {
            $strSymbol = "EUR";
        }

        $objZahlungsart = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($arrRow['zahlungsart_id']);
        return "<strong>" . $objZahlungsart->bezeichnung . ":</strong> ab " . sprintf("%01.2f", $arrRow['ab_einkaufpreis']) . " " . $strSymbol . " <span style=\"color:#b3b3b3; padding-left:3px;\">[" . sprintf("%01.2f", $arrRow['preis']) . " EUR Versandkosten]</span>";
    }
}

?>