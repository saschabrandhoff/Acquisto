<?php

/**
 * The base class for cancelling SofortDauerauftrag
 *
 * Copyright (c) 2011 Payment Network AG
 *
 * $Date: 2011-07-13 10:54:53 +0200 (Wed, 13 Jul 2011) $
 * @version SofortLib 1.3.0  $Id: sofortLib_cancel_sa.inc.php 1164 2011-07-13 08:54:53Z dehn $
 * @author Payment Network AG http://www.payment-network.com (integration@payment-network.com)
 *
 */

class SofortLib_CancelSa extends SofortLib_Abstract {
	
	var $parameters;
	var $file;
	
	var $cancelUrl = '';


	/**
	 * create new cancel object
	 *
	 * @param String $apikey your API-key
	 */
	function SofortLib_CancelSa($apikey='', $apiUrl = 'https://api.sofort.com/api/xml/') {
		list($userid, $projectId, $apikey) = explode(':', $apikey);
		$this->SofortLib($userid, $apikey, $apiUrl);
	}


	/**
	 * generate XML message
	 * @return string
	 */
	function toXml() {
		$msg = '<?xml version="1.0" encoding="UTF-8"?>';
		$msg .= $this->_arrayToXml($this->parameters, 'cancel_sa');

		return $msg;
	}
	
	/**
	 * 
	 * remove SofortDauerauftrag
	 * @param String $transaction Transaction ID
	 * @return SofortLib_CancelSa
	 */
	function removeSofortDauerauftrag($transaction) {
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
	 * Set the transaction you want to confirm/change
	 * @param String $arg Transaction Id
	 * @return SofortLib_CancelSa
	 */
	function setTransaction($arg) {
		$this->parameters['transaction'] = $arg;
		return $this;
	}
	
	
	function getCancelUrl() {
		return $this->cancelUrl;
	}
	
	/**
	 * Parser for response from server
	 * this callback will be called for every closing xml-tag
	 * @private
	 */
	function onParseTag($data, $tag){
			switch($tag) {
			case 'cancel_url':
				$this->cancelUrl = $data;
				break;
			default:
			break;
		}
	}
	
}