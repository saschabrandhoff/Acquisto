<?php
/**
 * class for refund/rueckbuchung
 *
 * Copyright (c) 2010 Payment Network AG
 *
 * $Date: 2011-07-08 13:39:39 +0200 (Fri, 08 Jul 2011) $
 * @version SofortLib 1.3.0  $Id: sofortLib_refund.inc.php 1150 2011-07-08 11:39:39Z dehn $
 * @author Payment Network AG http://www.payment-network.com (integration@payment-network.com)
 *
 */
class SofortLib_Refund extends SofortLib_Abstract
{
	var $response = array(array());
	var $data = array();


	function SofortLib_Refund($apikey='', $apiUrl = 'https://www.sofortueberweisung.de/payment/refunds') {
		list($userid, $projectId, $apikey) = explode(':', $apikey);
		$this->SofortLib($userid, $apikey, $apiUrl);
	}


	/**
	 * send this message and get response
	 *
	 * @return array transactionid=>status
	 */
	function sendRequest() {
		parent::sendRequest();

		return $this->getStatusArray();
	}


	/**
	 * generate XML message
	 * @return string
	 */
	function toXml() {
		$msg = '<?xml version="1.0" encoding="UTF-8"?>';
		$msg .= '<refunds>';

		if(is_array($this->data)) {
			foreach($this->data as $refund) {
				if(is_array($refund)) {
					$msg .= '<refund>';
						foreach ($refund as $var => $value) {
							$msg .= '<'.$var.'>'.$value.'</'.$var.">\n";
						}
					$msg .= '</refund>';
				}
			}
		}

		$msg .= '</refunds>';
		return $msg;
	}


	/**
	 * add a new refund to this message
	 *
	 * @param string $transaction transaction id of transfer you want to refund
	 * @param float $amount amount of money to refund, less or equal to amount of original transfer
	 * @param string $comment comment that will be displayed in  admin-menu later
	 * @return SofortLib_Refund $this
	 */
	function addRefund($transaction, $amount, $comment = '') {
		array_push($this->data, array('transaction' => $transaction, 'amount' => $amount, 'comment' => $comment));
		return $this;
	}


	/**
	 * Parser for response from server
	 * this callback will be called for every closing xml-tag
	 * @private
	 */
	function onParseTag($data, $tag){
		switch($tag) {
			case 'transaction':
			case 'amount':
			case 'comment':
			case 'status':
				if($this->_getParentTag() == 'refund') {
					$i = count($this->response)-1;
					$this->response[$i][$tag] = $data;
				}
				break;
			case 'code':
			case 'message':
				if($this->_getParentTag() == 'error') {
					$i = count($this->response)-1;
					$this->response[$i][$this->_getParentTag()][$tag] = $data;
				}
				break;
			case 'refund':
				if($this->_getParentTag() == 'refunds') {
					array_push($this->response, array());
				}
				break;
			case 'refunds':
				array_pop($this->response);
				break;
			default:
				break;
		}
	}


	function getTransactionId($i = 0) {
		return $this->response[$i]['transaction'];
	}


	function getAmount($i = 0) {
		return $this->response[$i]['amount'];
	}


	function getError($i = 0) {
		return parent::getError('all', $this->response[$i]);
	}


	function isError($i = 0) {
		return $this->response[$i]['status'] == 'error';
	}


	/* function doesnt exist anymore
	function getErrorCode($i = 0) {
		return parent::getErrorCode($this->response[$i]);
	}
	*/


	function getAsArray() {
		return $this->response;
	}


	function getStatusArray() {
		$ret = array();
		foreach($this->response as $transaction) {
			if($transaction['status'] == 'ok') {
				$ret[$transaction['transaction']] = 'ok';
			} else {
				$ret[$transaction['transaction']] = parent::getError($transaction);
			}
		}

		return $ret;
	}
}