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
$GLOBALS['TL_DCA']['tl_shop_gutscheine'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'switchToEdit'                => true,
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('code'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('code'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('zeitgrenze', 'using_counter'),
        'default'                     => '{allgemein},code,preis,kunden_id;{config_legend},using_counter,zeitgrenze;{state},aktiv;',
    ),

    'subpalettes' => array
    (
        'zeitgrenze'               => 'gueltig_von,gueltig_bis',
        'using_counter'            => 'max_using'
    ),


    // Fields
    'fields' => array
    (
        'code' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['code'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>20)
        ),
        'preis' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['preis'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'w50')
        ),
        'kunden_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['kunden_id'],
            'inputType'               => 'select',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50', 'includeBlankOption'=>true),
            'options_callback'        => array('tl_shop_gutscheine', 'getMember')
        ),
        'zeitgrenze' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['zeitgrenze'],
            'inputType'               => 'checkbox',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'isBoolean' => true, 'submitOnChange' => true),

        ),
        'using_counter' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['using_counter'],
            'inputType'               => 'checkbox',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'isBoolean' => true, 'submitOnChange' => true),

        ),
        'max_using' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['max_using'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>20)
        ),
        'gueltig_von' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['gueltig_von'],
            'default'                 => time(),
            'inputType'               => 'text',
            'exclude'                  => true,
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'maxlength'=>20, 'datepicker'=>$this->getDatePickerString(),'tl_class'=>'w50 wizard')
        ),
        'gueltig_bis' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['gueltig_bis'],
            'default'                 => time(),
            'inputType'               => 'text',
            'exclude'                  => true,
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'maxlength'=>20, 'datepicker'=>$this->getDatePickerString(),'tl_class'=>'w50 wizard')
        ),
        'aktiv' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['aktiv'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false)
        )
    )
);

class tl_shop_gutscheine extends Backend {
    public function __construct() {
        parent::__construct();
    }

    public function getMember() {
        $objMember = $this->Database->prepare("SELECT * FROM tl_member")->execute();
        while($objMember->next()) {
            $arrMember[$objMember->id] = $objMember->firstname . " " . $objMember->lastname;
        }

        return $arrMember;
    }
}
?>