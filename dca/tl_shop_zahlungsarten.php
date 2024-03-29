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
$GLOBALS['TL_DCA']['tl_shop_zahlungsarten'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => false,
        'switchToEdit'                => true,
        'onload_callback'             => array('tl_shop_zahlungsarten', 'onloadCallback')
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('payment_module'),
        'default'                     => '{title_legend},bezeichnung,payment_module,infotext;{configuration},configData;{useable},guests,groups;'
    ),


    // Fields
    'fields' => array
    (
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'payment_module' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['payment_module'],
            'inputType'               => 'select',
            'search'                  => false,
            'options_callback'        => array('tl_shop_zahlungsarten', 'getPaymentModules'),
            'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50', 'submitOnChange'=>true)
        ),
        'infotext' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['infotext'],
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'clr')
        ),
        'guests' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['guests'],
            'inputType'               => 'checkbox',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'groups' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_zahlungsarten']['groups'],
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_member_group.name',
            'search'                  => false,
            'eval'                    => array('multiple'=>true, 'mandatory'=>false, 'tl_class'=>'w50')
        ),
        'configData' => array(
            'label' => '',
            'input_field_callback' => array('tl_shop_zahlungsarten', 'nadas')
        )
    )
);

class tl_shop_zahlungsarten extends Backend
{
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->import('acquistoShopPayment', 'Payment');
    }

    public function nadas(DataContainer $DC) {
        /**
         * Speicherung des Feldes
         **/
        if($this->Input->post('FORM_SUBMIT') == 'tl_shop_zahlungsarten') {
            $this->Database->prepare("UPDATE tl_shop_zahlungsarten SET configData = '" . serialize($this->Input->Post('configData')) . "' WHERE id = ?")->execute($DC->id);
        }


        $objZahlungsart = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($DC->id);
        if(file_exists(TL_ROOT . '/system/modules/acquistoShop/paymentModules/' . $objZahlungsart->payment_module . '.php')) {
            include_once(TL_ROOT . '/system/modules/acquistoShop/paymentModules/' . $objZahlungsart->payment_module . '.php');
            $paymentModule = new $objZahlungsart->payment_module();

            $arrConfig = unserialize($objZahlungsart->configData);
            if(is_array($paymentModule->getConfigData())) {
                foreach($paymentModule->getConfigData() as $name => $element) {
                    $objElement = $GLOBALS['BE_FFL'][$element['inputType']];
                    $objElement = new $objElement($this->prepareForWidget($element, 'configData[' . $name . ']'));
                    $objElement->value = $arrConfig[$name];
                    $htmlData .= sprintf('<h3><label for="%s">%s</label></h3>', 'configData[' . $name . ']', $element['label'][0]);
                    $htmlData .= $objElement->generate();
                    $htmlData .= sprintf('<p class="tl_help">%s</p>', $element['label'][1]);
                }
            } else {
                $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurd keine Zahlungsart ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';
            }
        }  else {
            $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurd keine Zahlungsart ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';
        }

        return $htmlData;

    }

    public function getGroups()
    {
    }

    public function getPaymentModules() {
        return $this->Payment->getModules();
    }
}

?>