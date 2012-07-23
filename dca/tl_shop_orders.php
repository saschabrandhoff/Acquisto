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
$GLOBALS['TL_DCA']['tl_shop_orders'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_orders_items'),
        'enableVersioning'            => true,
        'switchToEdit'                => true,
        'closed'                      => true
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('tstamp'),
            'flag'                    => 6,
            'panelLayout'             => 'search,limit',
            'label_callback'          => array('tl_shop_orders', 'listItems')
        ),
        'label' => array
        (
            'fields'                  => array('tstamp'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[]</span>',
            'group_callback'          => array('tl_shop_orders', 'formatGroup'),
            'label_callback'          => array('tl_shop_orders', 'formatLabel'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['edit'],
                'href'                => 'table=tl_shop_orders_items',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ),
//            'customer' => array
//            (
//                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['customer'],
//                'href'                => 'table=tl_shop_orders_customers',
//                'icon'                => '/system/modules/acquistoShop/html/icons/user.png'
//            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},order_id,payed,versandzonen_id,zahlungsart_id;',
    ),


    // Fields
    'fields' => array
    (
        'order_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['order_id'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'versandzonen_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['versandzonen_id'],
            'inputType'               => 'select',
            'search'                  => false,
            'foreignKey'              => 'tl_shop_versandzonen.bezeichnung',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'zahlungsart_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['zahlungsart_id'],
            'inputType'               => 'select',
            'search'                  => false,
            'foreignKey'              => 'tl_shop_zahlungsarten.bezeichnung',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        ),
        'payed' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['payed'],
            'inputType'               => 'select',
            'search'                  => true,
            'options'                 => array('N'=>'Nein','Y'=>'Ja'),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
        )
    )
);

class tl_shop_orders extends Backend {
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->import('acquistoShop', 'Shop');
        $this->import('acquistoShopProdukt', 'Produkt');
    }

    public function formatGroup($arrRow) {
        return date("d.m.Y", strtotime($arrRow));
    }

    public function formatLabel($arrRow) {
        $objBestellung  = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id=?")->execute($arrRow['id']);
        $objGesamtpreis = $this->Database->prepare("SELECT SUM(preis * menge) AS fGesamt FROM tl_shop_orders_items WHERE pid = ?")->execute($objBestellung->id);
        $objPositionen  = $this->Database->prepare("SELECT * FROM tl_shop_orders_items WHERE pid = ?")->execute($objBestellung->id);
        $objCustomer    = $this->Database->prepare("SELECT * FROM tl_shop_orders_customers WHERE pid = ?")->limit(1)->execute($objBestellung->id);
        $objVersandzone = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?")->limit(1)->execute($objBestellung->versandzonen_id);
        $objZahlungsart = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($objBestellung->zahlungsart_id);

        $html  = null;
        $html .= '<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">';
        $html .= '<h3>Bestellung ' .         $this->Shop->generateOrderID($objBestellung->order_id) . ' um ' . date("H:i", $arrRow['tstamp']) . ', ' . $objCustomer->firstname . ' ' . $objCustomer->lastname . '</h3>';

        $html .= '<table cellpadding="0" cellspacing="0">';
        if($objCustomer->deliver_firstname OR $objCustomer->deliver_lastname OR $objCustomer->deliver_company OR $objCustomer->deliver_street OR $objCustomer->deliver_postalcode OR $objCustomer->deliver_city) {
            $html .= '    <tr>';
            $html .= '      <td>Rechnungsadresse:</td>';
            $html .= '      <td>' . $objCustomer->street . ', ' . $objCustomer->postalcode . ' ' . $objCustomer->city . '</td>';
            $html .= '    </tr>';
            $html .= '    <tr>';
            $html .= '      <td>Versandadresse:</td>';
            $html .= '      <td>' . $objCustomer->deliver_firstname . ' ' . $objCustomer->deliver_lastname . ', ' . $objCustomer->deliver_street . ', ' . $objCustomer->deliver_postalcode . ' ' . $objCustomer->deliver_city . '</td>';
            $html .= '    </tr>';
        } else {
            $html .= '    <tr>';
            $html .= 'Rechnungs- &amp; Versandadresse: ' . $objCustomer->street . ', ' . $objCustomer->postalcode . ' ' . $objCustomer->city . '<br>';
            $html .= '    </tr>';
        }
        $html .= '</table>';


        $html .= '<table cellpadding="0" cellspacing="0" width="100%">';
        $html .= '    <tr>';
        $html .= '        <td colspan="4"><hr></td>';
        $html .= '    </tr>';
        $html .= '    <tr>';
        $html .= '        <td><b>Bezeichnung</b></td>';
        $html .= '        <td align="right"><b>Menge</b></td>';
        $html .= '        <td align="right"><b>EP</b></td>';
        $html .= '        <td align="right"><b>Summe</b></td>';
        $html .= '    </tr>';

        while($objPositionen->next()) {
            $objPosition = (object) $objPositionen->row();
            $objProdukt  = $this->Produkt->load($objPosition->produkt_id, $objPosition->attribute);
            $objSteuer = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && tstamp<? ORDER  BY tstamp DESC")->limit(1)->execute($objPositionen->steuersatz_id, $objBestellung->tstamp);
            $arrSteuer[$objSteuer->satz]['gesamt'] = $arrSteuer[$objSteuer->satz]['gesamt'] + ($objPosition->menge * $objPosition->preis);
            $arrSteuer[$objSteuer->satz]['steuer'] = $arrSteuer[$objSteuer->satz]['gesamt'] - ($arrSteuer[$objSteuer->satz]['gesamt'] / (($objSteuer->satz + 100) / 100));

            $html .= '    <tr>';
            $html .= '        <td>' . $objPosition->bezeichnung . '</td>';
            $html .= '        <td align="right">' . $objPosition->menge . '</td>';
            $html .= '        <td align="right">' . sprintf("%01.2f", $objPosition->preis) . ' EUR</td>';
            $html .= '        <td align="right">' . sprintf("%01.2f", ($objPosition->menge * $objPosition->preis)) . ' EUR</td>';
            $html .= '    </tr>';
            
            if(is_array($objProdukt->filterArray())) {
                $html .= '    <tr>';
                $html .= '        <td colspan="4">';
                $html .= '            <ul>';                    

                foreach($objProdukt->filterArray() as $item) {
                    $html .= '            <li>' . $item->title . ': ' . $item->selection . '</li>';                    
                }                            

                $html .= '            </ul>';                    
                $html .= '        </td>';
                $html .= '    </tr>';
            } 
        }

        $html .= '    <tr>';
        $html .= '        <td colspan="4"><hr></td>';
        $html .= '    </tr>';
        $html .= '    <tr>';
        $html .= '        <td align="right" colspan="3">Gesamtpreis:</td>';
        $html .= '        <td align="right">' . sprintf("%01.2f", $objGesamtpreis->fGesamt) . ' EUR</td>';
        $html .= '    </tr>';

        if(is_array($arrSteuer)) {
            foreach($arrSteuer as $Satz => $Steuer) {
                $html .= '    <tr>';
                $html .= '        <td align="right" colspan="3">enth. MwSt. ' . $Satz . '% auf ' . sprintf("%01.2f", $Steuer['gesamt']) .  'EUR:</td>';
                $html .= '        <td align="right">' . sprintf("%01.2f", $Steuer['steuer']) . ' EUR</td>';
                $html .= '    </tr>';
            }
        }

        $html .= '    <tr>';
        $html .= '        <td align="right" colspan="3">zzgl. Versandkosten (' . $objVersandzone->bezeichnung . ' / ' . $objZahlungsart->bezeichnung . '):</td>';
        $html .= '        <td align="right">' . sprintf("%01.2f", $objBestellung->versandpreis) . ' EUR</td>';
        $html .= '    </tr>';
        $html .= '    <tr>';
        $html .= '        <td align="right" colspan="3">Endpreis:</td>';
        $html .= '        <td align="right">' . sprintf("%01.2f", $objBestellung->versandpreis + $objGesamtpreis->fGesamt) . ' EUR</td>';
        $html .= '    </tr>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
}

?>