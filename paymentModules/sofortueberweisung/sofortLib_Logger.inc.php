<?php
/// \cond
/**
 *
 * Logger class
 * Extend this class to implement more advanced logging features like logging with email or SMS support
 * Overwrite those functions with your own functionality
 * @internal
 */
class SofortLibLogger {

	var $fp = null;
	var $maxFilesize = 1048576;

	function SofortLibLogger() {
		$this->maxFilesize = 1 * 1024 * 1024;
	}

	function log($msg, $uri) {
		if($this->logRotate($uri)) {
			$this->fp = fopen($uri, 'w');
			fclose($this->fp);
		}
		$this->fp = fopen($uri, 'a');
		fwrite($this->fp,  '['.date('Y-m-d H:i:s').'] ' . $msg . "\n");
		fclose($this->fp);
	}

	/**
	 * Copy the content of the logfile to a backup file if size got too big
	 * @param URI $uri
	 */
	function logRotate($uri) {
		$date = date('Y-m-d_h-i-s', time());
		if($this->fp != null && filesize($uri) != false && filesize($uri) >= $this->maxFilesize) {
			$oldUri = $uri;
			$newUri = $uri.'_'.$date;
			rename($oldUri, $newUri);
			if(file_exists($oldUri)) {
				unlink($oldUri);
			}
			return true;
		}
		return false;
	}

}
/// \endcond