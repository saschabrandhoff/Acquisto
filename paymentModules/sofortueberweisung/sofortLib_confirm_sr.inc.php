<?php
/**
 * This class is for confirming and changing invoices
 *
 * eg: $confirmObj = new SofortLib_ConfirmSr('yourapikey');
 *
 * $confirmObj->confirmInvoice('1234-456-789654-31321')->sendRequest();
 *
 * Copyright (c) 2010 Payment Network AG
 *
 * $Date: 2011-07-07 11:28:22 +0200 (Thu, 07 Jul 2011) $
 * @version SofortLib 1.3.0  $Id: sofortLib_confirm_sr.inc.php 1123 2011-07-07 09:28:22Z dehn $
 * @author Payment Network AG http://www.payment-network.com (integration@payment-network.com)
 *
 */
class SofortLib_ConfirmSr extends SofortLib_Abstract
{
	var $parameters;
	var $file;


	/**
	 * create new confirm object
	 *
	 * @param String $apikey your API-key
	 */
	function SofortLib_ConfirmSr($apikey='', $apiUrl = 'https://api.sofort.com/api/xml/') {
		list($userid, $projectId, $apikey) = explode(':', $apikey);
		$this->SofortLib($userid, $apikey, $apiUrl);
	}


	/**
	 * generate XML message
	 * @return string
	 */
	function toXml() {
		$msg = '<?xml version="1.0" encoding="UTF-8"?>';
		$msg .= $this->_arrayToXml($this->parameters, 'confirm_sr');

		return $msg;
	}


	/**
	 * Parser for response from server
	 * this callback will be called for every closing xml-tag
	 * @private
	 */
	function onParseTag($data, $tag){
			switch($tag) {
			case 'download_url':
				$this->file = $data;
				break;
			default:
			break;
		}
	}


	/**
	 * Set the transaction you want to confirm/change
	 * @param String $arg Transaction Id
	 * @return SofortLib_ConfirmSr
	 */
	function setTransaction($arg) {
		$this->parameters['transaction'] = $arg;
		return $this;
	}


	/**
	 * set a comment for refunds
	 * @param string $arg
	 */
	function setComment($arg) {
		$this->parameters['comment'] = $arg;
		return $this;
	}


	/**
	 * add one item to the cart if you want to change the invoice
	 *
	 * @param string $productNumber product number, EAN code, ISBN number or similar
	 * @param string $title description of this title
	 * @param double $unit_price gross price of one item
	 * @param int $productType product type number see manual
	 * @param string $description additional description of this item
	 * @param int $quantity default 1
	 * @param int $tax tax in percent, default 19
	 */
	function addItem($item_id, $product_number, $product_type, $title, $description, $quantity, $unit_price, $tax) {
		$unit_price = number_format($unit_price, 2, '.','');
		$tax = number_format($tax, 2, '.','');
		$quantity = intval($quantity);
		$this->parameters['items'][] = array(
			'item_id' => $item_id,
			'product_number' => $product_number,
			'product_type' => $product_type, 
			'title' => $title, 
			'description' => $description,
			'quantity' => $quantity, 
			'unit_price' => $unit_price, 
			'tax' => $tax
		);
	}
	
	
	// TODO: implement removal of items
	function removeItem($productId, $quantity = 0) {
		if(!isset($this->parameters['items'][$productId])) {
			return false;
		}
		elseif ($quantity = -1) {
			unset($this->parameters['items'][$productId]);
			return true;
		}
		$this->parameters['items'][$productId]['quantity'] = $quantity;	
		return true;
	}
	
	
	// TODO: implement changing the quantity for products for given product number
	function changeUnitQuantity($productId, $unitQuantitiy) {
		
	}
	
	// TODO: implement changing the unit price for given product number
	function changeUnitPrice($productNumber, $unitPrice) {
		if($unitPrice < 0) return false;
	}


	/**
	 * cancel the invoice
	 * @param string $transaction the transaction id
	 * @return SofortLib_ConfirmSr
	 */
	function cancelInvoice($transaction = '') {
		if(empty($transaction) && array_key_exists('transaction', $this->parameters)) {
			$transaction = $this->parameters['transaction'];
		}

		if(!empty($transaction)) {
			$this->parameters = NULL;
			$this->parameters['transaction'] = $transaction;
			$this->parameters['items'] = array();
		}

		return $this;
	}


	/**
	 * confirm the invoice
	 * @param string $transaction the transaction id
	 * @return SofortLib_ConfirmSr
	 */
	function confirmInvoice($transaction = '') {
		if(empty($transaction) && array_key_exists('transaction', $this->parameters)) {
			$transaction = $this->parameters['transaction'];
		}

		if(!empty($transaction)) {
			$this->parameters = NULL;
			$this->parameters['transaction'] = $transaction;
		}

		return $this;
	}


	/**
	 * after you you changed/confirmed an invoice you
	 * can download the new invoice-pdf with this function
	 * @return string url
	 */
	function getInvoiceUrl() {
		return $this->file;
	}
}