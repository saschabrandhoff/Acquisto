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
$GLOBALS['TL_DCA']['tl_shop_produkte_attribute'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_produkte',
        'ctable'                      => array('tl_shop_produkte_varianten'),
        'enableVersioning'            => false,
        'switchToEdit'                => true,
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('pattribut_id'),
            'headerFields'            => array('produktnummer', 'bezeichnung'),
            'flag'                    => 11,
            'panelLayout'             => 'search,limit',
            'disableGrouping'         => true,
            'child_record_callback'   => array('tl_shop_produkte_attribute', 'listItems'),
        ),
        'label' => array
        (
            'fields'                  => array('attribut_id:tl_shop_attribute.bezeichnung'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_attribute']['edit'],
                'href'                => 'table=tl_shop_produkte_varianten',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_attribute']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_attribute']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_attribute']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},attribut_id,pattribut_id;',
    ),


    // Fields
    'fields' => array
    (
        'attribut_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte_attribute']['attribut_id'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_attribute.bezeichnung',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'pattribut_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte_attribute']['pattribut_id'],
            'inputType'               => 'select',
            'options_callback'        => array('tl_shop_produkte_attribute', 'prevAttribute'),
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'includeBlankOption'=>true)
        )
    )
);

class tl_shop_produkte_attribute extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listItems($arrRow)
    {
        $objAttribut  = $this->Database->prepare("SELECT * FROM tl_shop_attribute WHERE id=?")->limit(1)->execute($arrRow['attribut_id']);
        $objSum       = $this->Database->prepare("SELECT COUNT(*) AS NumRows FROM tl_shop_produkte_varianten WHERE pid=?")->execute($arrRow['id']);
        $objVarianten = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE pid=?")->execute($arrRow['id']);

        while($objVarianten->next())
        {
            $arrElement = $objVarianten->row();

            if($arrElement['produkt_attribut_id'])
            {
                $strAdd = $this->buildStructure($arrElement['produkt_attribut_id']);
            }

            $strList .= "<li style=\"color:#b3b3b3;\">" . $strAdd . " &raquo; " . $arrElement['bezeichnung'] . "</li>";
        }

        if($arrRow['pattribut_id'])
        {
            $objPAAttribut  = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE id=?")->limit(1)->execute($arrRow['pattribut_id']);
            $objPAttribut   = $this->Database->prepare("SELECT * FROM tl_shop_attribute WHERE id=?")->limit(1)->execute($objPAAttribut->attribut_id);
            $strAdd        = " | Elternattribut: " . $objPAttribut->bezeichnung;
        }

        if($objSum->NumRows)
        {
            $strReturnVal = "<div>" . $objAttribut->bezeichnung . " <span style=\"color:#b3b3b3; padding-left:3px;\">[ID: " . $arrRow['id'] . " | Varianten: " . $objSum->NumRows . $strAdd . "]</span><hr /><ul>" . $strList . "</ul></div>";
        }
        else
        {
            $strReturnVal = "<div>" . $objAttribut->bezeichnung . " <span style=\"color:#b3b3b3; padding-left:3px;\">[ID: " . $arrRow['id'] . " | Varianten: " . $objSum->NumRows . $strAdd . "]</span></div>";
        }

        return $strReturnVal;
    }

    public function prevAttribute($row)
    {
        $objRow = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE id=?")->execute($row->id);
        $objAttributes = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE pid=? && id!=?")->execute($objRow->pid, $objRow->id);

        while($objAttributes->next()) {
            $objAttribut  = $this->Database->prepare("SELECT * FROM tl_shop_attribute WHERE id=?")->limit(1)->execute($objAttributes->attribut_id);
            $arrOptions[$objAttributes->id] = $objAttribut->bezeichnung;
        }

        return $arrOptions;
#        echo $objRow->pid;
    }

    public function buildStructure($previous_id)
    {
        if($previous_id)
        {
            $objParent = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE id=?")->execute($previous_id);

            if($objParent->produkt_attribut_id)
            {
                $strAdd = $this->buildStructure($objParent->produkt_attribut_id);
            }
        }

        return $strAdd . " &raquo; " . $objParent->bezeichnung;
    }
}

?>