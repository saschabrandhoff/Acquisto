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
$GLOBALS['TL_DCA']['tl_shop_steuersaetze'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_steuer',
        'enableVersioning'            => true
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('gueltig_ab'),
            'headerFields'            => array('bezeichnung'),
            'flag'                    => 12,
            'panelLayout'             => 'search,limit',
            'child_record_callback'   => array('tl_shop_steuersaetze', 'listItems'),
            'disableGrouping'         => true
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuersaetze']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuersaetze']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuersaetze']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuersaetze']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{settings},satz,gueltig_ab;',
    ),


    // Fields
    'fields' => array
    (
        'satz' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_steuersaetze']['satz'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'gueltig_ab' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_steuersaetze']['gueltig_ab'],
            'default'                 => time(),
            'inputType'               => 'text',
            'exclude'                  => true,
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'maxlength'=>20, 'datepicker'=>$this->getDatePickerString(),'tl_class'=>'w50 wizard')
        ),
    )
);

class tl_shop_steuersaetze extends Backend {
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listItems($arrRow) {
        $row = sprintf("%01.2f", $arrRow['satz']) . '% MwSt. <span style="color:#b3b3b3; padding-left:3px;">[' . date("d.m.Y", $arrRow['gueltig_ab']) . ']</span>';
        return "<div>" . $row . "</div>\n";
    }


}

?>