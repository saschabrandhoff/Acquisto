<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  pixelSpread.de - 2009
 * @author     Sascha Brandhoff (brandhoff@pixelspread.de)
 * @package    Acquisto Webshop
 * @license    LGPL
 * @filesource
 */


/**
 * Backend module
 */
$GLOBALS['BE_MOD']['acquisto'] = array(
    'acquistoShopHersteller' => array
    (
        'tables'     => array('tl_shop_hersteller'),
        'icon'       => 'system/modules/acquistoShop/html/icons/cog.png',
    ),
    'acquistoShopProdukte' => array
    (
        'tables'           => array('tl_shop_produkte', 'tl_shop_produkte_attribute', 'tl_shop_produkte_varianten'),
        'icon'             => 'system/modules/acquistoShop/html/icons/book.png',
        'importData'       => array('acquistoShopBackend', 'importData'),

    ),
    'acquistoShopWarengruppen' => array
    (
        'tables'     => array('tl_shop_warengruppen'),
        'icon'       => 'system/modules/acquistoShop/html/icons/application_cascade.png',
    ),
    'acquistoShopGutscheine' => array
    (
        'tables'     => array('tl_shop_gutscheine'),
        'icon'       => 'system/modules/acquistoShop/html/icons/lightbulb.png',
    )
);


$GLOBALS['BE_MOD']['acquisto_Orders'] = array(
    'acquistoShopOrders' => array
    (
        'tables'     => array('tl_shop_orders', 'tl_shop_orders_items', 'tl_shop_orders_customer'),
        'icon'       => 'system/modules/acquistoShop/html/icons/basket.png',
    )
);

$GLOBALS['BE_MOD']['acquisto_Settings'] = array(
    'acquistoShopAttribute' => array
    (
        'tables'     => array('tl_shop_attribute'),
        'icon'       => 'system/modules/acquistoShop/html/icons/book_link.png',
    ),
    'acquistoShopEinstellungen' => array
    (
        'tables'     => array('tl_shop_settings'),
        'icon'       => 'system/modules/acquistoShop/html/icons/wrench.png',
    ),
    'acquistoShopMengeneinheit' => array
    (
        'tables'     => array('tl_shop_mengeneinheit'),
        'icon'       => 'system/modules/acquistoShop/html/icons/table.png',
    ),
    'acquistoShopSteuern' => array
    (
        'tables'     => array('tl_shop_steuer', 'tl_shop_steuersaetze'),
        'icon'       => 'system/modules/acquistoShop/html/icons/ruby.png',
    ),
    'acquistoShopShippingZones' => array
    (
        'tables'     => array('tl_shop_versandzonen', 'tl_shop_versandzonen_varten'),
        'icon'       => 'system/modules/acquistoShop/html/icons/package.png',
    ),
    'acquistoShopZahlungsarten' => array
    (
        'tables'     => array('tl_shop_zahlungsarten'),
        'icon'       => 'system/modules/acquistoShop/html/icons/money.png',
    ),
    'acquistoShopExport' => array
    (
        'tables'     => array('tl_shop_export'),
        'icon'       => 'system/modules/acquistoShop/html/icons/page_code.png',
        'export'     => array('tl_shop_export', 'exportData'),
    )
);


/**
 * Frontend module
 */
array_insert($GLOBALS['FE_MOD']['acquisto'], 0, array
(
    'acquistoShop_Produktdetails'  => 'mod_acquistoShop_Produktdetails',
    'acquistoShop_Produktliste'    => 'mod_acquistoShop_Produktliste',
    'acquistoShop_Breadcrumb'      => 'mod_acquistoShop_Breadcrumb',
    'acquistoShop_Suche'           => 'mod_acquistoShop_Suche',
    'acquistoShop_Suchergebnis'    => 'mod_acquistoShop_Suchergebnis',
    'acquistoShop_Versand'         => 'mod_acquistoShop_Versand',
    'acquistoShop_Warengruppen'    => 'mod_acquistoShop_Warengruppen',
    'acquistoShop_Warenkorb'       => 'mod_acquistoShop_Warenkorb',
    'acquistoShop_TagCloud'        => 'mod_acquistoShop_TagCloud',
    'acquistoShop_Gutschein'       => 'mod_acquistoShop_Gutschein',
    'acquistoShop_Bestellliste'    => 'mod_acquistoShop_Bestellliste',
    'acquistoShop_Bestelldetails'  => 'mod_acquistoShop_Bestelldetails',
    'acquistoShop_Filterliste'     => 'mod_acquistoShop_Filterliste',
    'acquistoShop_AGB'             => 'mod_acquistoShop_AGB'
));

array_insert($GLOBALS['FE_MOD']['acquisto_widget'], 0, array
(
    'acquistoShop_WarenkorbWidget' => 'mod_acquistoShop_WarenkorbWidget',
    'acquistoShop_Produktfiler'    => 'mod_acquistoShop_Produktfilter',
    'acquistoShop_Recent'          => 'mod_acquistoShop_Recent'
));

/**
 * Form fields
 **/
$GLOBALS['BE_FFL']['categorieTree'] = 'CategorieTree';
$GLOBALS['BE_FFL']['preisWizard']   = 'PreisWizard';

/**
 * Content Element
 */
//$GLOBALS['TL_CTE']['acquistoShop']['ContentElement'] = 'ContentElement';


$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/acquistoShop/html/acquistoShop.js';
$GLOBALS['TL_HEAD'][] = '<!--

    Acquisto Webshop - http://contao-acquisto.de :: Licensed under GNU/LGPL
    Copyright (c)2012 by Sascha Brandhoff :: Extensions of Acquisto are copyright of their respective owners
    Visit the project website at http://www.contao-acquisto.de for more information

//-->';

$GLOBALS['TL_HOOKS']['executePreActions']['loadCategorietree']      = array('acquistoShopAjax', 'PRECategorieActions');
$GLOBALS['TL_HOOKS']['executePostActions']['loadCategorietree']     = array('acquistoShopAjax', 'POSTCategorieActions');
$GLOBALS['TL_HOOKS']['getSystemMessages']['acquistoSystemMessages'] = array('acquistoShopMessages', 'getSystemMessages');
$GLOBALS['TL_HOOKS']['getSystemMessages']['acquistoMessages']       = array('acquistoShopMessages', 'getMessages');
$GLOBALS['TL_HOOKS']['getSystemMessages']['getDeveloperMessages']   = array('acquistoShopMessages', 'getDeveloperMessages');


$GLOBALS['TL_CONFIG']['acquistoShopVersion'] = '1.0.0';

if (TL_MODE == 'BE') {
    $GLOBALS['TL_CSS'][] = 'system/modules/acquistoShop/html/css/backend.css|all';
    if($GLOBALS['TL_CONFIG']['serialnumber']) {
    
//        $GLOBALS['BE_MOD']['acquisto_Settings']['ModuleAcquistoPortal'] = array
//        (
//            'callback'     => 'ModuleAcquistoPortal',
//            'icon'       => 'system/modules/acquistoShop/html/icons/book_link.png',
//            'stylesheet' => 'system/modules/acquistoShop/html/style.css'
//        );
    
    }
}

?>