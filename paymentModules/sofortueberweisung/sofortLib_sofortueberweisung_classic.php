<?php
require_once 'sofortLib_sofortueberweisung_classic_notification.inc.php';
/**
 * Setup a sofortueberweisung.de session using the classic api
 * after the configuration of the configuration you will receive
 * an url and a transaction id, your customer should be redirected to this url
 *
 *
 * Called by the sofortLib.php/sofortLib_ideal_classic.php etc.
 * $sofort->new SofortLib_SofortueberweisungClassic( $userid, $projectid, $password [, $hashfunction='sha1'] );
 * $sofort->set...(); //set params for Hashcalculation
 * $sofort->set...(); //set more params for Hashcalculation
 * $sofort->getPaymentUrl();
 * Notice: sometimes getPaymentUrl() must be overwritten by calling class because of changed hash-params
 *
 *
 * Copyright (c) 2010 Payment Network AG
 *
 * $Date: 2011-07-07 11:28:22 +0200 (Thu, 07 Jul 2011) $
 * @version SofortLib 1.3.0  $Id: sofortLib_sofortueberweisung_classic.php 1123 2011-07-07 09:28:22Z dehn $
 * @author Payment Network AG http://www.payment-network.com (integration@payment-network.com)
 *
 */
class SofortLib_SofortueberweisungClassic
{
	var $params = array();
	var $password;
	var $userid;
	var $projectid;
	var $hashfunction;
	
	var $paymentUrl = 'https://www.sofortueberweisung.de/payment/start';


	function SofortLib_SofortueberweisungClassic($userid, $projectid, $password, $hashfunction='sha1', $paymentUrl = 'https://www.sofortueberweisung.de/payment/start') {
		$this->password = $password;
		$this->userid = $userid;
		$this->projectid = $projectid;
		$this->hashfunction = strtolower($hashfunction);
		$this->params['encoding'] = 'UTF-8';

		$this->params['user_id'] = $this->userid;
		$this->params['project_id'] = $this->projectid;
		
		$this->paymentUrl = $paymentUrl;
	}


	function setAmount($arg, $currency = 'EUR') {
		$this->params['amount'] = $arg;
		$this->params['currency_id'] = $currency;
	}


	function setSenderHolder($senderHolder) {
		$this->params['sender_holder'] = $senderHolder;
	}


	function setSenderAccountNumber($senderAccountNumber) {
		$this->params['sender_account_number'] = $senderAccountNumber;
	}

	/**
	 *
	 * Set the reason (Verwendungszweck) for sending money
	 * @param string $arg
	 * @param string $arg2
	 */
	function setReason($arg, $arg2='') {
		$arg = preg_replace('#[^a-zA-Z0-9+-\.,]#', ' ', $arg);
		$arg2 = preg_replace('#[^a-zA-Z0-9+-\.,]#', ' ', $arg2);

		$this->params['reason_1'] = $arg;
		$this->params['reason_2'] = $arg2;

		return $this;
	}


	function addUserVariable($arg) {
		$i = 0;
		while ($i < 6) {
			if(array_key_exists('user_variable_'.$i, $this->params))
				$i++;
			else
				break;
		}
		$this->params['user_variable_'.$i] = $arg;

		return $this;
	}


	function getPaymentUrl() {
		//fields required for hash
		$hashfields = array(
						'user_id',
						'project_id',
						'sender_holder',
						'sender_account_number',
						'sender_bank_code',
						'sender_country_id',
						'amount','currency_id',
						'reason_1','reason_2',
						'user_variable_0',
						'user_variable_1',
						'user_variable_2',
						'user_variable_3',
						'user_variable_4',
						'user_variable_5',
		);

		//build parameter-string for hashing
		$hashstring = '';
		foreach ($hashfields as $value) {
			if(array_key_exists($value, $this->params)) {
				$hashstring.= $this->params[$value];
			}
			$hashstring .= '|';
		}

		$hashstring .= $this->password;

		//calculate hash
		$hash = $this->getHashHexValue($hashstring, $this->hashfunction);
		$this->params['hash'] = $hash;

		//create parameter string
		$paramString = '';
		foreach ($this->params as $key => $value) {
			$paramString .= $key.'='.urlencode($value).'&';
		}
		$paramString = substr($paramString, 0, -1); //remove last &

		return $this->paymentUrl.'?'.$paramString;
	}


	function isError() {
		return false;
	}


	function getError() {
		return false;
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


	/**
	 * @param int [optional] $length length of return value, default 24
	 * @return string
	 */
	function generatePassword($length = 24) {
		$randomValue = '';

		//if php >= 5.3 and openssl installed we will use its more secure random generator
		if(function_exists('openssl_random_pseudo_bytes')) {
			$p = base64_encode(openssl_random_pseudo_bytes($length, $strong)); //output is base64: a-zA-Z0-9/+
			if($strong === TRUE)
			{
				$randomValue = preg_replace('#[^A-Za-z0-9]#', '', $p); //remove all special chars
				$randomValue = substr($randomValue, 0, $length); //base64 is about 33% longer, so needs to get truncated
			}
		}

		//fallback to mt_rand for php < 5.3
		if(strlen($randomValue) < $length)
		{
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; //62 chars 0-9A-Za-z
			$charactersLength = strlen($characters)-1;

			//select some random characters from all characters
			for ($i = 0; $i < $length; $i++) {
				$randomValue .= $characters[mt_rand(0, $charactersLength)];
			}
		}

		return $randomValue;
	}
}