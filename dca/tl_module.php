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
 * Add a palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Produktliste']    = '{title_legend},name,type;{config_legend},contaoShop_jumpTo,contaoShop_numberOfItems,perPage,acquistoShop_elementsPerRow,acquistoShop_listTemplate;{list_images:hide},contaoShop_imageSrc,acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating;{categorie_image},contaoShop_imageSize,contaoShop_imageMargin,contaoShop_imageFloating;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Suchergebnis']    = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo,contaoShop_numberOfItems,perPage,acquistoShop_elementsPerRow,acquistoShop_listTemplate;{contaoShop_image},contaoShop_imageSize,contaoShop_imageMargin,contaoShop_imageFloating;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Breadcrumb']      = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Suche']           = '{title_legend},name,headline,type;{config_legend},contaoShop_imageSrc,contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Warenkorb']       = '{title_legend},name,headline,type;{config_legend},acquistoShop_emailTemplate,acquistoShop_emailTyp,contaoShop_jumpTo,acquistoShop_AGBFile;{fields},acquistoShop_selFields,acquistoShop_mandatoryFields;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_WarenkorbWidget'] = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Warengruppen']    = '{title_legend},name,headline,type;{config_legend},contaoShop_levelOffset,contaoShop_showLevel,contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_AGB']             = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Versand']         = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_TagCloud']        = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Bestellliste']    = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Recent']          = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo,contaoShop_numberOfItems;{list_images:hide},contaoShop_imageSrc,acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Gutschein']       = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Filterliste']     = '{title_legend},name,headline,type;{config_legend},acquistoShop_tags,acquistoShop_zustand,acquistoShop_hersteller,acquistoShop_produkttype,acquistoShop_warengruppe,acquistoShop_markedProducts;{list_settings},contaoShop_jumpTo,contaoShop_numberOfItems,perPage,acquistoShop_elementsPerRow,acquistoShop_listTemplate;{list_images:hide},contaoShop_imageSrc,acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating;{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Produktdetails']  = '{title_legend},name,type;{contaoShop_image},contaoShop_imageSrc,contaoShop_imageSize,contaoShop_imageMargin,contaoShop_imageFloating,contaoShop_imageFullsize;{galerie_options:hide},acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating,acquistoShop_galerie_imageFullsize;{socialmedia},contaoShop_socialFacebook,contaoShop_socialTwitter;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Produktfiler']    = '{title_legend},name,headline,type;{config_legend};{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquistoShop_Bestelldetails']  = '{title_legend},name,headline,type;{config_legend};{expert_legend:hide},cssID,space;';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'acquistoShop_allowComments';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['acquistoShop_allowComments'] = 'com_disableCaptcha,com_requireLogin,com_bbcode,com_moderate,com_template,acquistoShop_commentsPerPage,com_order,acquistoShop_commentsNotify';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_jumpTo'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_jumpTo'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_numberOfItems'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_numberOfItems'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageSize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
    'exclude'                 => true,
    'inputType'               => 'imageSize',
    'options'                 => array('crop', 'proportional', 'box'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageMargin'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
    'exclude'                 => true,
    'inputType'               => 'trbl',
    'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
);


$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageFloating'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
    'exclude'                 => true,
    'inputType'               => 'radioTable',
    'options'                 => array('above', 'left', 'right', 'below'),
    'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC']
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageFullsize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageFullsize'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_levelOffset'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_levelOffset'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_showLevel'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_showLevel'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_menuTyp'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_menuTyp'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('root' => 'Root', 'sub' => 'Sub'),
    'eval'                    => array('mandatory'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageSrc'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageSrc'],
    'inputType'               => 'fileTree',
    'search'                  => false,
    'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif', 'tl_class' => 'clr')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_socialFacebook'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_socialFacebook'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_socialTwitter'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_socialTwitter'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_AGBFile'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_AGBFile'],
    'inputType'               => 'fileTree',
    'search'                  => false,
    'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'pdf')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTemplate'],
    'default'                 => 'warenkorb_email_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_acquistoShop', 'get_emailTemplate'),
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_listTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_listTemplate'],
    'default'                 => 'warenkorb_email_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_acquistoShop', 'get_listTemplates'),
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailTyp'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTyp'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('text' => 'Text E-Mail', 'html' => 'HTML E-Mail'),
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_elementsPerRow'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_elementsPerRow'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageSize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
    'exclude'                 => true,
    'inputType'               => 'imageSize',
    'options'                 => array('crop', 'proportional', 'box'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageMargin'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
    'exclude'                 => true,
    'inputType'               => 'trbl',
    'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
);


$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageFloating'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
    'exclude'                 => true,
    'inputType'               => 'radioTable',
    'options'                 => array('above', 'left', 'right', 'below'),
    'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC']
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageFullsize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageFullsize'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_markedProducts'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_markedProducts'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_tags'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_tags'],
    'default'                 => '',
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_hersteller'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_hersteller'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_shop_hersteller.bezeichnung',
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_produkttype'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_produkttype'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('normal'=>'Normales Produkt', 'digital'=>'Digitales Produkt', 'variant'=> 'Varianten Produkt'),
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_zustand'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_zustand'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('new' => 'Neu', 'used'=>'Gebraucht', 'refurbished' => 'Erneuert'),
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_allowComments'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_allowComments'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_commentsNotify'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_commentsNotify'],
    'default'                 => 'notify_admin',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_news_archive'],
    'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_commentsPerPage'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_commentsPerPage'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_warengruppe'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_warengruppe'],
    'exclude'                 => true,
    'inputType'               => 'categorieTree',
    'foreignKey'              => 'tl_shop_warengruppen.title',
    'eval'                    => array('tl_class'=>'', 'includeBlankOption' => true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_selFields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options'                 => $GLOBALS['TL_ACQCONF']['FIELDS'],
    'eval'                    => array('multiple'=>true, 'mandatory'=>false)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_mandatoryFields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_mandatoryFields'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options'                 => $GLOBALS['TL_ACQCONF']['FIELDS'],
    'eval'                    => array('multiple'=>true, 'mandatory'=>false)
);

//$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_Template'] = array
//(
//    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_Template'],
//    'default'                 => 'contaoshop_produktliste_',
//    'exclude'                 => true,
//    'inputType'               => 'select',
//    'options_callback'        => array('tl_module_contaoShop', 'get_contaoShopTemplates'),
//    'eval'                    => array('cols'=>4, 'tl_class'=>'w50')
//);

class tl_module_acquistoShop extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function get_contaoShopTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('cs_', $dc->activeRecord->pid);
    }

    public function get_listTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('acquisto_list_', $dc->activeRecord->pid);
    }

    public function get_emailTemplate(DataContainer $dc)
    {
        return $this->getTemplateGroup('warenkorb_email', $dc->activeRecord->pid);
    }
}

?>