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
$GLOBALS['TL_DCA']['tl_shop_produkte_varianten'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_produkte_attribute',
        'enableVersioning'            => true
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('bezeichnung'),
            'headerFields'            => array('attribut_id'),
            'flag'                    => 12,
            'panelLayout'             => 'search,limit',
            'disableGrouping'         => true,
            'child_record_callback'   => array('tl_shop_produkte_varianten', 'listItems'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuer']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuer']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuer']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_steuer']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},bezeichnung,produkt_attribut_id,preise;',
    ),


    // Fields
    'fields' => array
    (
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'produkt_attribut_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['produkt_attribut_id'],
            'inputType'               => 'select',
            'search'                  => true,
            'options_callback'        => array('tl_shop_produkte_varianten', 'getOptions'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'includeBlankOption'=>true)
        ),
        'preise' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise'],
            'inputType'               => 'preisWizard',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false)
        )
    )
);

class tl_shop_produkte_varianten extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
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

    public function listItems($arrRow)
    {
        if($arrRow['produkt_attribut_id'])
        {
            $strAdd = $this->buildStructure($arrRow['produkt_attribut_id']) . " &raquo; ";
        }

        return "<span style=\"color:#b3b3b3; padding-left:3px;\">" . $strAdd . "</span>" . $arrRow['bezeichnung'];
    }

    public function getOptions($objData)
    {
        $objParent   = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE id=?")->execute($objData->id);
        $objAttribut = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE id=?")->execute($objParent->pid);

        if($objAttribut->pattribut_id)
        {
            $objWhile = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE pid=?")->execute($objAttribut->pattribut_id);
            while($objWhile->next())
            {
                if($objWhile->produkt_attribut_id)
                {
                    $strAdd = $this->buildStructure($objWhile->produkt_attribut_id) . " &raquo; ";
                }

                $arrOptions[$objWhile->id] = $strAdd . $objWhile->bezeichnung;
            }
        }

        return $arrOptions;
    }
}

?>