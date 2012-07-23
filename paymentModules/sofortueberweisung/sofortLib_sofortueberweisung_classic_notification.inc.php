<?php
/**
 * This class should be called when you receive a notification about
 * a status change.
 *
 *
 * Copyright (c) 2010 Payment Network AG
 *
 * $Date: 2011-07-07 11:28:22 +0200 (Thu, 07 Jul 2011) $
 * @version SofortLib 1.3.0  $Id: sofortLib_sofortueberweisung_classic_notification.inc.php 1123 2011-07-07 09:28:22Z dehn $
 * @author Payment Network AG http://www.payment-network.com (integration@payment-network.com)
 *
 */
class SofortLib_SofortueberweisungClassicNotification
{
	var $params = array();
	var $password;
	var $userid;
	var $projectid;
	var $hashfunction;

	var $hashCheck = false;

	function SofortLib_SofortueberweisungClassicNotification($userid, $projectid, $password, $hashfunction='sha1') {
		$this->password = $password;
		$this->userid = $userid;
		$this->projectid = $projectid;
		$this->hashfunction = $hashfunction;
	}


	function getNotification($request) {
		$fields = array(
			'transaction', 'user_id', 'project_id',
			'sender_holder', 'sender_account_number', 'sender_bank_code', 'sender_bank_name', 'sender_bank_bic', 'sender_iban', 'sender_country_id',
			'recipient_holder',	'recipient_account_number', 'recipient_bank_code', 'recipient_bank_name', 'recipient_bank_bic',	'recipient_iban', 'recipient_country_id',
			'international_transaction', 'amount', 'currency_id', 'reason_1', 'reason_2', 'security_criteria',
			'user_variable_0',	'user_variable_1', 'user_variable_2', 'user_variable_3', 'user_variable_4',	'user_variable_5',
			'created'
		);
		$this->params = array();
		foreach($fields as $key) {
			$this->params[$key] = $request[$key];
		}
		$this->params['project_password'] = $this->password;

		$validationhash = $this->getHashHexValue(implode('|', $this->params), $this->hashfunction);
		$messagehash = $request['hash'];

		$this->hashCheck = ($validationhash === $messagehash) ? true : false;

		return $this;
	}


	/**
	 * @param string $data string to be hashed
	 * @return string the hash
	 */
	function getHashHexValue($data, $hashfunction='sha1') {
		if($hashfunction == 'sha1')
			return sha1($data);
		if($hashfunction == 'md5')
			return md5($data);
		//mcrypt installed?
		if(function_exists('hash') && in_array($hashfunction, hash_algos()))
			return hash($hashfunction, $data);

		return false;
	}


	function isError() {
		return false;
	}


	function getError() {
		return false;
	}


	function getTransaction() {
		return $this->params['transaction'];
	}


	function getAmount() {
		return $this->params['amount'];
	}


	function getUserVariable($i=0) {
		return $this->params['user_variable_'.$i];
	}


	function getCurrency() {
		return $this->params['currency_id'];
	}


	function getTime() {
		return $this->params['created'];
	}
}