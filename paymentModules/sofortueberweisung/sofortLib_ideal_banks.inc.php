<?php
class SofortLib_iDeal_Banks extends SofortLib_Abstract {
	
	var $banks = '';
	var $count = 0;
	
	function SofortLib_iDeal_Banks($configurationKey, $apiUrl) {
		list($userid, $projectId, $apikey) = explode(':', $configurationKey);
		$this->SofortLib($userid, $apikey, $apiUrl.'/banks');
	}
	
	function toXml(){}
	
	function onParseTag($data, $tag) {
		switch($tag) {
			case 'code':
			case 'name':
			case 'bank':
				if($this->_getParentTag() == 'bank') {
					$this->banks[$this->count][$tag] = $data;
				}
				if($this->_getParentTag() == 'banks') {
					$this->count++;
				}
				break;
			default:
			break;
		}
			
	}
	
	function getBanks() {
		return $this->banks;
	}
	
}