<?php
/// \cond
/**
 * interface for Payment Network XML-Api
 *
 * this class implements basic http authentication and a xml-parser
 * for parsing response messages
 *
 * requires libcurl and openssl
 *
 * Copyright (c) 2010 Payment Network AG
 *
 * $Date: 2011-07-07 11:28:22 +0200 (Thu, 07 Jul 2011) $
 * @version SofortLib 1.3.0  $Id: sofortLib_abstract.inc.php 1123 2011-07-07 09:28:22Z dehn $
 * @author Payment Network AG http://www.payment-network.com (integration@payment-network.com)
 * @internal
 *
 */
class SofortLib_Abstract extends SofortLib
{

	/**
	 * generate XML message
	 * @return string
	 */
	function toXml() {
		trigger_error('Missing implementation of toXml()', E_USER_NOTICE);
	}


	/**
	 * Override this callback
	 * its being called everytime we find a closing xml-tag
	 *
	 * @protected
	 * @param string $data data of this tag
	 * @param string $tag name of this tag
	 */
	function onParseTag($data, $tag) {
		trigger_error('Missing implementation of onParseTag()', E_USER_NOTICE);
	}


	/**
	 * send this message and get response
	 * save all warnings - errors are only saved if no payment-url is send from pnag
	 *
	 * @return SofortLib_TransactionData $this
	 */
	function sendRequest() {
		$data = $this->_sendMessage($this->toXml());
		$this->_initParser();
		$this->_parse($data);

		//$this->enableLog();  //set enable to aktivate following lines
		$this->log(get_class($this) . ' -> '. $this->formatXmlString($this->toXml()));
		$this->log(get_class($this) . ' <- '. $this->formatXmlString($data));

		return $this;
	}
}
/// \endcond