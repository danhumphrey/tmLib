<?php
/**
  * HttpRequest class
  * 
  * @package tmLib
  * @subpackage Http
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * HttpRequest class for encalpsulating $_GET and $_POST data.
 * 
 * @package tmLib
 * @subpackage Http
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class HttpRequest extends DataSet 
{
	private $isPost;
	
	/**
	 * HttpRequest class for encalpsulating $_GET and $_POST data.
	 * @access public
	 */
	function __construct() {
		parent::__construct();
		if (get_magic_quotes_gpc()) {
			$this->removeSlashes($_GET);
			$this->removeSlashes($_POST);
		}
		
		if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
			$this->data = empty($_POST) ? array() : $_POST;
			$this->isPost = true;
		} else {
			$this->data = empty($_GET) ? array() : $_GET;
			$this->isPost = false;
		}
		@$this->data['PATH_INFO'] = $_SERVER['PATH_INFO'];
	}
	
	/**
	 * Checks if request method is POST
	 * @access public
	 * @return boolean true if a the request method is POST
	 */
	function isPost() {
		return $this->isPost;
	}
		
	/**
	 * Removes slashes from a variable (handles arrays recursively)
	 * @access private
	 * @param string $var the variable to strip
	 */
	private function removeSlashes(&$var) {
		if (is_array($var)) {
			foreach ($var as $name => $value) {
				if (is_array($value)) {
					$this->removeSlashes($value);
				} else {
					$var[$name] = stripslashes($value);
				}
			}
		} else {
			$var = stripslashes($var);
		}
	}
}
?>