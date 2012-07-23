<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * @package    News
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class mod_acquistoShop_Warenkorb extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_warenkorb';
    protected $strVersandberechnung;
    protected $floatBerechnung = 0;


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate() {
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO WARENKORB ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->import('FrontendUser', 'Member');
        $this->Import('acquistoShopBasket', 'Basket');
        $this->Import('acquistoShopPayment', 'Payment');
        $this->Import('acquistoShopProdukt', 'Produkt');
        $this->Import('acquistoShopGutschein', 'Gutschein');
        $this->Import('acquistoShop', 'Shop');

        switch(strtolower($GLOBALS['TL_CONFIG']['versandberechnung'])) {
            case "gewicht":
                $this->strVersandberechnung = "gewicht";
                $this->floatBerechnung = $this->Basket->getWeight();
                break;;
            default:
                $this->strVersandberechnung = "preis";
                $this->floatBerechnung = $this->Basket->getCosts();
                break;;
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile() {
        if($this->Input->Get('remove')) {
            $this->Basket->remove($this->Input->Get('remove'));
            $this->redirect($this->getBasketUrl($this->replaceInsertTags($this->Shop->getInsertTagPID())));
        }

        if($this->contaoShop_jumpTo) {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');
        }
        
        $arrBasket_Data = $this->Session->get('basket_data');
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));



        switch($this->Input->Get('do'))
        {
            case 'customer':
                if (FE_USER_LOGGED_IN) {
                    $this->import('FrontendUser', 'User');
                    $objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->limit(1)->execute($this->User->id);
                    $arrMember = $objMember->row();
                } else {
                    $arrMember = $this->Session->get('Customer');
                }

                if($this->Input->Post('action'))
                {
                    $arrCustomer = $this->Input->Post('customer');

                    if($arrCustomer['firstname'] && $arrCustomer['lastname'] && $arrCustomer['street'] && $arrCustomer['postal'] && $arrCustomer['city'] && $arrCustomer['email'])
                    {
                        $this->Session->set('Customer', $arrCustomer);
                        $this->redirect($this->getBasketUrl($this->replaceInsertTags($this->Shop->getInsertTagPID()))  . "?do=payment-and-shipping");
                    }
                }

                $this->Template = new FrontendTemplate('mod_warenkorb_customer');
                $this->Template->Member   = $arrMember;
                $this->Template->Customer = $this->Session->get('Customer');
                break;;
            case 'payment-and-shipping':
                $arrWidgetData = $this->Basket->itemNum();

                if($this->Input->Post('paymentMethod') OR $this->Input->Post('shippingMethod')) {
                    $arrBasket_Data['paymentMethod']  = $this->Input->Post('paymentMethod');
                    $arrBasket_Data['shippingMethod'] = $this->Input->Post('shippingMethod');

                    $this->Session->set('basket_data', $arrBasket_Data);

                    if($this->Input->Post('action')) {
                        $this->redirect($this->getBasketUrl($this->replaceInsertTags($this->Shop->getInsertTagPID()))  . "?do=agb");
                    }
                }


                $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen;")->execute();

                while($objVersandzonen->next())
                {
                    $arrItem = $objVersandzonen->row();
                    if(!$arrBasket_Data['shippingMethod']) {
                        $arrBasket_Data['shippingMethod'] = $arrItem['id'];
                    }

                    $arrVersandzonen[] = $arrItem;
                }

                $objVersandarten = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE pid = ? && ab_einkaufpreis < ? ORDER BY ab_einkaufpreis ASC;")->execute($arrBasket_Data['shippingMethod'], $this->floatBerechnung);


                while($objVersandarten->next()) {
                    if($objVersandarten->ab_einkaufpreis < $this->Basket->getCosts()) {
                    $objZahlungsarten = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandarten->zahlungsart_id);
                    while($objZahlungsarten->next())
                    {
                        $arrZahlungsart = $objZahlungsarten->row();
                        $arrZahlungsart['preis']                 = $objVersandarten->preis;
                        $arrZahlungsart['ab_einkaufpreis']       = $objVersandarten->ab_einkaufpreis;
                        $arrZahlungsart['sendID']                = $objVersandarten->id;
                        $arrZahlungsarten[$objZahlungsarten->id] = $arrZahlungsart;
                    }
                    }
                }

                $this->Template = new FrontendTemplate('mod_warenkorb_payment_and_shipping');
                $this->Template->Zahlungsarten = $arrZahlungsarten;
                $this->Template->Versandzonen  = $arrVersandzonen;

                $this->Template->sel_pm = $arrBasket_Data['paymentMethod'];
                $this->Template->sel_sm = $arrBasket_Data['shippingMethod'];
                break;;
            case 'agb':
                if($this->Input->Post('agb'))
                {
                    $arrBasket_Data['agb'] = 1;
                    $this->Session->set('basket_data', $arrBasket_Data);
                    $this->redirect($this->getBasketUrl($this->replaceInsertTags($this->Shop->getInsertTagPID()))  . "?do=check-and-order");
                }

                $this->Template = new FrontendTemplate('mod_warenkorb_agb');
                $this->Template->AGB = $GLOBALS['TL_CONFIG']['agb'];
                $this->Template->Widerruf = $GLOBALS['TL_CONFIG']['widerruf'];
                $this->Template->sel_agb = $arrBasket_Data['agb'];
                break;;
            case 'check-and-order':
                $objWidget  = (object) $this->Basket->itemNum();
                $arrSteuern = $this->Basket->getTaxes();

                $objVersandart    = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE id = ?;")->execute($arrBasket_Data['paymentMethod']);
                $objVersandzone   = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?;")->execute($objVersandart->pid);
                $objZahlungsarten = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandart->zahlungsart_id);

                $arrGutscheine = $this->Gutschein->getList();
                if(is_array($arrGutscheine)) {
                    foreach($arrGutscheine as $Gutschein) {
                        $dblGutschein_Gesamt = $dblGutschein_Gesamt + $Gutschein->preis;
                    }

                    if(is_array($arrSteuern)) {
                        foreach($arrSteuern as $steuersatz => $values) {
                            $arrSteuern[$steuersatz]['summe'] = $arrSteuern[$steuersatz]['summe'] - ($dblGutschein_Gesamt / count($arrSteuern));
                            $arrSteuern[$steuersatz]['wert']  = round($arrSteuern[$steuersatz]['summe'] - ($arrSteuern[$steuersatz]['summe'] / ((100 + $steuersatz) / 100)), 2);

                        }
                    }
                }

                $this->Template->Gutscheine   = $arrGutscheine;


                $this->Template = new FrontendTemplate('mod_warenkorb_check_and_order');

                $this->Template->Gutscheine    = $arrGutscheine;
                $this->Template->Steuern       = $arrSteuern;
                $this->Template->Produktliste  = $this->Basket->loadProducts($strUrl);

                $this->Template->Zahlungsart   = (object) $objZahlungsarten->row();
                $this->Template->Versandart    = (object) $objVersandart->row();
                $this->Template->Versandzone   = (object) $objVersandzone->row();
                $this->Template->Customer      = (object) $this->Session->get('Customer');

                $this->Template->Gesamtpreis   = $this->Basket->getCosts() - $dblGutschein_Gesamt;
                $this->Template->Versandpreis  = $objVersandart->preis;
                $this->Template->Endpreis      = ($this->Basket->getCosts() - $dblGutschein_Gesamt) + $objVersandart->preis;

                $this->Template->paymentModule = sprintf($this->Payment->frontendTemplate($objZahlungsarten->payment_module, $objZahlungsarten->id), $this->generateFrontendUrl($objPage->fetchAssoc()) . "?do=pay", (($this->Basket->getCosts() - $dblGutschein_Gesamt) + $objVersandart->preis));;

                break;;
            case 'pay':
                $objVersandart    = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE id = ?;")->execute($arrBasket_Data['paymentMethod']);
                $objVersandzone   = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?;")->execute($objVersandart->pid);
                $objZahlungsarten = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandart->zahlungsart_id);
                $arrGutscheine    = $this->Gutschein->getList();

                $boolReturn = $this->Payment->pay($objZahlungsarten->payment_module, $objZahlungsarten->id);
                if($boolReturn) {
                    if(is_array($_SESSION['acquistoShop'])) {
#                        $this->Basket->writeOrder($this->User->id);
                        if (FE_USER_LOGGED_IN) {
                            $this->import('FrontendUser', 'User');
                            $this->Database->prepare("INSERT INTO tl_shop_orders (tstamp, member_id, versandzonen_id, zahlungsart_id, versandart_id, gutscheine, payed, versandpreis) VALUES(" . time() . ", " . $this->User->id . ", " . $arrBasket_Data['shippingMethod'] . ", " . $objZahlungsarten->id . ", " . $arrBasket_Data['paymentMethod'] . ", '" . serialize($this->Gutschein->Checkout($this->User->id)) . "', 0, " . $objVersandart->preis . ");")->execute();
                        } else {
                            $this->Database->prepare("INSERT INTO tl_shop_orders (tstamp, member_id, versandzonen_id, zahlungsart_id, versandart_id, gutscheine, payed, versandpreis) VALUES(" . time() . ", 0, " . $arrBasket_Data['shippingMethod'] . ", " . $objZahlungsarten->id . ", " . $arrBasket_Data['paymentMethod'] . ", '" . serialize($this->Gutschein->Checkout(0)) . "', 0," . $objVersandart->preis . ");")->execute();
                        }

                        $objOrder = $this->Database->prepare("SELECT LAST_INSERT_ID() AS lid FROM tl_shop_orders")->execute();
                        $arrCustomer = $this->Session->get('Customer');

                        $objCounter = $this->Database->prepare("SELECT COUNT(*) AS totalNumbers FROM tl_shop_orders WHERE tstamp > ?")->execute(mktime(0, 0, 0, 1, 1, date("Y")));
                        if($objCounter->totalNumbers) {
                            $objCounter = $this->Database->prepare("SELECT MAX(order_id) AS maxID FROM tl_shop_orders WHERE tstamp > ?")->execute(mktime(0, 0, 0, 1, 1, date("Y")));
                            $orderID = $objCounter->maxID + 1;
                        } else {
                            $orderID = 1;
                        }

                        $this->Database->prepare("UPDATE tl_shop_orders SET order_id = " . $orderID . " WHERE id = ?")->execute($objOrder->lid);

                        $this->Database->prepare("INSERT INTO tl_shop_orders_customers (pid, tstamp, firstname, lastname, street, postalcode, city, email, deliver_firstname, deliver_lastname, deliver_company, deliver_street, deliver_postalcode, deliver_city) VALUES(" . $objOrder->lid . ", " . time() . ", '" . $arrCustomer['firstname'] . "', '" . $arrCustomer['lastname'] . "', '" . $arrCustomer['street'] . "', '" . $arrCustomer['postal'] . "', '" . $arrCustomer['city'] . "', '" . $arrCustomer['email'] . "', '" . $arrCustomer['deliver_firstname'] . "', '" . $arrCustomer['deliver_lastname'] . "',  '" . $arrCustomer['deliver_company'] . "', '" . $arrCustomer['deliver_street'] . "', '" . $arrCustomer['deliver_postal'] . "', '" . $arrCustomer['deliver_city'] . "');")->execute();

                        foreach($this->Basket->loadProducts($strUrl) as $hash => $data) {
                            $objProdukt = $this->Produkt->load($data['id'], $data['attributes']);
                            $objSteuer  = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && gueltig_ab < NOW() ORDER BY gueltig_ab DESC")->limit(1)->execute($data['steuer']);

                            $arrProdukt = $data;
                            $arrProdukt['kaufmenge']   = $data['menge'];
                            $arrProdukt['kaufpreis']   = sprintf("%01.2f", $objProdukt->getPreis($data['menge']));

                            $arrEndpreis['zwischensumme'] = sprintf("%01.2f", $arrEndpreis['zwischensumme'] + ($data['menge'] * $objProdukt->getPreis($data['menge'])));
                            $arrEndpreis['steuer'][$objSteuer->satz]['gesamt'] = sprintf("%01.2f", $arrEndpreis['steuer'][$objSteuer->satz]['gesamt'] + ($data['menge'] * $objProdukt->getPreis($data['menge'])));
                            $arrEndpreis['steuer'][$objSteuer->satz]['steuer'] = sprintf("%01.2f", $arrEndpreis['steuer'][$objSteuer->satz]['gesamt'] - ($arrEndpreis['steuer'][$objSteuer->satz]['gesamt'] / (($objSteuer->satz + 100) / 100)));

                            $arrShopping[] = $arrProdukt;
                            $this->Database->prepare("INSERT INTO tl_shop_orders_items (pid, tstamp, produkt_id, bezeichnung, menge, preis, attribute, steuersatz_id) VALUES(" . $objOrder->lid . ", " . time() . ", " . $data['id'] . ", '" . $data['bezeichnung'] . "', '" . $data['menge'] . "', '" . $objProdukt->getPreis($data['menge']) . "', '" . str_replace("'", "\"", $data['attributes'])  . "', '" . $data['steuer'] . "')")->execute();
                        }

                        $arrVersand = array(
                            'versandzone'   => $objVersandzone->bezeichnung,
                            'versandkosten' => sprintf("%01.2f", $objVersandart->preis),
                            'zahlungsart'   => $objZahlungsarten->bezeichnung
                        );

                        if(is_array($arrGutscheine)) {
                            foreach($arrGutscheine as $Gutschein) {
                                $dblGutschein_Gesamt = $dblGutschein_Gesamt + $Gutschein->preis;
                            }

                            if(is_array($arrEndpreis['steuer'])) {
                                foreach($arrEndpreis['steuer'] as $steuersatz => $values) {
                                    $arrEndpreis['steuer'][$steuersatz]['gesamt'] = $arrEndpreis['steuer'][$steuersatz]['gesamt'] - ($dblGutschein_Gesamt / count($arrEndpreis['steuer']));
                                    $arrEndpreis['steuer'][$steuersatz]['steuer']  = round($arrEndpreis['steuer'][$steuersatz]['gesamt'] - ($arrEndpreis['steuer'][$steuersatz]['gesamt'] / ((100 + $steuersatz) / 100)), 2);

                                }
                            }
                        }

                        $arrEndpreis['zwischensumme'] = $arrEndpreis['zwischensumme'] - $dblGutschein_Gesamt;
                        $arrEndpreis['endpreis'] = sprintf("%01.2f", ($arrEndpreis['zwischensumme'] - $dblGutschein_Gesamt) + $objVersandart->preis);

                        $mailTemplate = new FrontendTemplate($this->acquistoShop_emailTemplate);
                        $mailTemplate->Gutscheine = $arrGutscheine;
                        $mailTemplate->Shopping   = $arrShopping;
                        $mailTemplate->Customer   = $arrCustomer;
                        $mailTemplate->Endpreis   = $arrEndpreis;
                        $mailTemplate->Settings   = $GLOBALS['TL_CONFIG'];
                        $mailTemplate->Versand    = $arrVersand;

                        $objEmail = new Email();
                        $objEmail->from = $GLOBALS['TL_CONFIG']['bestell_email'];
                        $objEmail->fromName = $GLOBALS['TL_CONFIG']['firmenname'];
                        $objEmail->subject = 'Bestellung ' . $this->Shop->generateOrderID($orderID);

                        if(!$this->acquistoShop_emailTyp OR $this->acquistoShop_emailTyp = 'text') {
                            $objEmail->text = $mailTemplate->parse();
                        } elseif($this->acquistoShop_emailTyp == 'html') {
                            $objEmail->html = $mailTemplate->parse();
                        }

                        $objEmail->attachFile(TL_ROOT . '/' . $this->acquistoShop_AGBFile);
                        if($GLOBALS['TL_CONFIG']['bestell_email'] && $arrCustomer['email']) {
                            $objEmail->sendBcc($GLOBALS['TL_CONFIG']['bestell_email']);
                            $objEmail->sendTo($arrCustomer['email']);
                        }

                        $this->Basket->clear();
                    }

                    $this->Template = new FrontendTemplate('mod_warenkorb_pay');
                }


                break;;
            default:
                /**
                 * Standart Warenkorb ansicht
                 * Status: fertig
                 **/                                                  
                
                $objWidget = (object) $this->Basket->itemNum();
                
                $arrGutscheine = $this->Gutschein->getList();
                if(is_array($arrGutscheine)) {
                    foreach($arrGutscheine as $Gutschein) {
                        $dblGutschein_Gesamt = $dblGutschein_Gesamt + $Gutschein->preis;
                    }

                    if(is_array($arrSteuern)) {
                        foreach($arrSteuern as $steuersatz => $values) {
                            $arrSteuern[$steuersatz]['summe'] = $arrSteuern[$steuersatz]['summe'] - ($dblGutschein_Gesamt / count($arrSteuern));
                            $arrSteuern[$steuersatz]['wert']  = round($arrSteuern[$steuersatz]['summe'] - ($arrSteuern[$steuersatz]['summe'] / ((100 + $steuersatz) / 100)), 2);

                        }
                    }
                }

                $this->Template->Gutscheine   = $arrGutscheine;
                $this->Template->Steuern      = $this->Basket->getTaxes();
                $this->Template->Endpreis     = $this->Basket->getCosts() - $dblGutschein_Gesamt;
                $this->Template->Produktliste = $this->Basket->loadProducts($strUrl);
                break;;
        }

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
        $arrWidgetData = $this->Basket->itemNum();
        $this->Template->WarenkorbUrl = $this->generateFrontendUrl($objPage->fetchAssoc());
    }

    public function getBasketUrl($id) {
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($id);
        return $this->generateFrontendUrl($objPage->fetchAssoc());
    }
}

?>