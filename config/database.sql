-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- --------------------------------------------------------

--
-- Table `tl_shop_attribute`
--

CREATE TABLE `tl_shop_attribute` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `preisberechnung` char(64) NOT NULL default '',
  `feldtyp` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_gutscheine`
--

CREATE TABLE `tl_shop_gutscheine` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `kunden_id` int(10) NOT NULL default '0',
  `bestellung_id` int(10) NOT NULL default '0',
  `preis` float NOT NULL default '0',
  `code` varchar(20) NOT NULL default '',
  `aktiv` int(1) NOT NULL default '0',
  `zeitgrenze` char(1) NOT NULL default '',
  `gueltig_von` int(10) NOT NULL default '0',
  `gueltig_bis` int(10) NOT NULL default '0',
  `using_counter` char(1) NOT NULL default '',
  `max_using` int(10) NOT NULL default '0',
  `is_used` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_hersteller`
--

CREATE TABLE `tl_shop_hersteller` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `strasse` varchar(255) NOT NULL default '',
  `postleitzahl` varchar(255) NOT NULL default '',
  `ort` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_mengeneinheit`
--

CREATE TABLE `tl_shop_mengeneinheit` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `einheit` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_orders`
--

CREATE TABLE `tl_shop_orders` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `member_id` int(10) NOT NULL default '0',
  `order_id` int(10) NOT NULL default '0',
  `versandzonen_id` int(10) NOT NULL default '0',
  `zahlungsart_id` int(10) NOT NULL default '0',
  `versandart_id` int(10) NOT NULL default '0',
  `gutscheine` longtext NOT NULL,
  `payed` char(1) NOT NULL default '',
  `versandpreis` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_orders_items`
--

CREATE TABLE `tl_shop_orders_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `produkt_id` int(10) NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `menge` float NOT NULL default '0',
  `preis` float NOT NULL default '0',
  `attribute` text NOT NULL,
  `steuersatz_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_orders_customers`
--

CREATE TABLE `tl_shop_orders_customers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `postalcode` char(5) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `deliver_firstname` varchar(255) NOT NULL default '',
  `deliver_lastname` varchar(255) NOT NULL default '',
  `deliver_company` varchar(255) NOT NULL default '',
  `deliver_street` varchar(255) NOT NULL default '',
  `deliver_postalcode` char(5) NOT NULL default '',
  `deliver_city` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_produkte`
--

CREATE TABLE `tl_shop_produkte` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `alias` varchar(128) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `hersteller` int(10) NOT NULL default '0',
  `produktnummer` varchar(255) NOT NULL default '',
  `bezeichnung` varchar(255) NOT NULL default '',
  `gewicht` float NOT NULL default '0',
  `zustand` char(255) NOT NULL default '',
  `tags` varchar(255) NOT NULL default '',
  `steuer` int(10) NOT NULL default '0',
  `mengeneinheit` int(10) NOT NULL default '0',
  `inhalt` varchar(10) NOT NULL default '',
  `berechnungsmenge` varchar(10) NOT NULL default '',
  `warengruppen` text NOT NULL,
  `teaser` text NOT NULL,
  `beschreibung` longtext NOT NULL,
  `galerie` text NOT NULL,
  `preview_image` varchar(255) NOT NULL default '',
  `digital_product` blob NOT NULL,
  `preise` text NOT NULL,
  `aktiv` int(1) NOT NULL default '0',
  `marked` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_produkte_attribute`
--

CREATE TABLE `tl_shop_produkte_attribute` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `attribut_id` int(10) NOT NULL default '0',
  `pattribut_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_produkte_varianten`
--

CREATE TABLE `tl_shop_produkte_varianten` (
  `id` int(10) NOT NULL auto_increment,
  `tstamp` int(10) NOT NULL default '0',
  `pid` int(10) NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `produkt_attribut_id` int(10) NOT NULL default '0',
  `preise` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_steuer`
--

CREATE TABLE `tl_shop_steuer` (
  `id` int(10) NOT NULL auto_increment,
  `tstamp` int(10) NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_steuersaetze`
--

CREATE TABLE `tl_shop_steuersaetze` (
  `id` int(10) NOT NULL auto_increment,
  `tstamp` int(10) NOT NULL default '0',
  `pid` int(10) NOT NULL default '0',
  `satz` float NOT NULL default '0',
  `gueltig_ab` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_versandzonen`
--

CREATE TABLE `tl_shop_versandzonen` (
  `id` int(10) NOT NULL auto_increment,
  `tstamp` int(10) NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `laender` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_warengruppen`
--

CREATE TABLE `tl_shop_warengruppen` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `language` varchar(32) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `seo_keywords` text NOT NULL,
  `seo_description` text NOT NULL,
  `beschreibung` longtext NOT NULL,
  `imageSrc` varchar(255) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `jumpToPage` int(10) unsigned NOT NULL default '0',
  `published` char(1) NOT NULL default '',
  `member_groups` text NOT NULL,
  `cssID` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_zahlungsarten`
--

CREATE TABLE `tl_shop_zahlungsarten` (
  `id` int(10) NOT NULL auto_increment,
  `tstamp` int(10) NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `payment_module` varchar(32) NOT NULL default '',
  `groups` text NOT NULL,
  `guests` int(1) NOT NULL default '0',
  `infotext` text NOT NULL,
  `configData` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_versandzonen_varten`
--

CREATE TABLE `tl_shop_versandzonen_varten` (
  `id` int(10) NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0',
  `tstamp` int(10) NOT NULL default '0',
  `ab_einkaufpreis` float NOT NULL default '0',
  `preis` float NOT NULL default '0',
  `steuersatz_id` int(10) NOT NULL default '0',
  `zahlungsart_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_module`
--

CREATE TABLE `tl_module` (
  `contaoShop_jumpTo` int(10) NOT NULL default '0',
  `contaoShop_numberOfItems` smallint(5) NOT NULL default '0',
  `contaoShop_imageSize` varchar(64) NOT NULL default '',
  `contaoShop_imageMargin` varchar(128) NOT NULL default '',
  `contaoShop_imageFloating` varchar(32) NOT NULL default '',
  `contaoShop_imageFullsize` char(1) NOT NULL default '',
  `contaoShop_imageSrc` varchar(255) NOT NULL default '',
  `contaoShop_Template` varchar(255) NOT NULL default '',
  `contaoShop_menuTyp` varchar(32) NOT NULL default '',
  `contaoShop_levelOffset` smallint(5) NOT NULL default '0',
  `contaoShop_showLevel` smallint(5) NOT NULL default '0',
  `contaoShop_socialFacebook` smallint(1) NOT NULL default '0',
  `contaoShop_socialTwitter` smallint(1) NOT NULL default '0',
  `acquistoShop_emailTemplate` varchar(255) NOT NULL default '',
  `acquistoShop_listTemplate` varchar(255) NOT NULL default '',
  `acquistoShop_setOrder` char(1) NOT NULL default '',
  `acquistoShop_listOrder` varchar(64) NOT NULL default '',
  `acquistoShop_listOrderBy` varchar(255) NOT NULL default '',
  `acquistoShop_AGBFile` varchar(255) NOT NULL default '',
  `acquistoShop_emailTyp` char(4) NOT NULL default '',
  `acquistoShop_markedProducts` char(1) NOT NULL default '',
  `acquistoShop_elementsPerRow` int(10) NOT NULL default '0',
  `acquistoShop_galerie_imageSize` varchar(64) NOT NULL default '',
  `acquistoShop_galerie_imageMargin` varchar(128) NOT NULL default '',
  `acquistoShop_galerie_imageFloating` varchar(32) NOT NULL default '',
  `acquistoShop_galerie_imageFullsize` char(1) NOT NULL default '',
  `acquistoShop_tags` varchar(255) NOT NULL default '',
  `acquistoShop_zustand` varchar(255) NOT NULL default '',
  `acquistoShop_hersteller` int(10) NOT NULL default '0',
  `acquistoShop_produkttype` varchar(255) NOT NULL default '',
  `acquistoShop_warengruppe` char(255) NOT NULL default '',
  `acquistoShop_allowComments` char(1) NOT NULL default '',
  `acquistoShop_commentsOrder` char(10) NOT NULL default '',
  `acquistoShop_commentsTemplate` varchar(255) NOT NULL default '',
  `acquistoShop_commentsPerPage` char(10) NOT NULL default '',
  `acquistoShop_commentsNotify` varchar(255) NOT NULL default ''
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_shop_export`
--

CREATE TABLE `tl_shop_export` (
  `id` int(10) NOT NULL auto_increment,
  `tstamp` int(10) NOT NULL default '0',
  `bezeichnung` varchar(255) NOT NULL default '',
  `produktDetails` int(10) NOT NULL default '0',
  `exportModule` varchar(255) NOT NULL default '',
  `exportFile` varchar(255) NOT NULL default '',
  `exportSettings` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;