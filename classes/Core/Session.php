<?php
/**
  * Session class
  *
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * Session class for wrapping php's session functionality
 *
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class Session extends DataSet {
	/**
	 * Constructs the Session class
	 * @access public
	 */
	function __construct() {
		@session_start();
	}
	/**
	 * Checks if a key exists in the session
	 * @access public
	 * @param string $key the name of the variable to check
	 * @return boolean true if a variable named $key exists in the request
	 */
	function keyExists($key) {
		$this->data = $_SESSION;
		return parent::keyExists($key);
	}

	/**
	 * Checks if an array key exists in a session array variable
	 * @access public
	 * @param string $key the key of the array in the session
	 * @param string $arrayKey the key of the array
	 * @return boolean true if a variable named $key exists in the request
	 */
	function arrayKeyExists($key,$arrayKey) {
		$this->data = $_SESSION;
		return parent::arrayKeyExists($key, $arrayKey);
	}
	
	/**
	 * Returns a variable from the session if it exists
	 * @access public
	 * @param string $key the name of the variable to retrieve
	 * @return mixed the value of the variable named $key or null if the variable
	 */
	function get($key) {
		$this->data = $_SESSION;
		return parent::get($key);
	}
	/**
	 * Returns an array key variable from the Session if it exists
	 * @access public
	 * @param string $key the key to the array
	 * @param string $arrayKey the key of the array variable to retrieve
	 * @return mixed the value of the array variable or null if the variable does not exist
	 */
	function getArrayKey($key, $arrayKey) {
		$this->data = $_SESSION;
		return parent::getArrayKey($key, $arrayKey);
	}
	/**
	 * Returns the session as an array
	 * @access public
	 * @return array the entire session
	 */
	function getData() {
		return empty($_SESSION) ? array() : $_SESSION;
	}
	/**
	 * Sets a variable in the session with the name $key and value $value
	 * @access public
	 * @param string $key the name of the variable
	 * @param mixed $value the value of the variable
	 */
	function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	/**
	 * Sets an array key
	 *
	 * @param string $key the key of the array in the session
	 * @param string $arrayKey the key to set within the array 
	 * @param mixed $value the value of the array key
	 */
	function setArrayKey($key, $arrayKey, $value)
	{
		$_SESSION[$key][$arrayKey] = $value;
	}
	
	/**
	 * Removes a variable from the Session
	 *
	 * @param string $key the key to remove
	 */
	function remove($key) {
		if($this->keyExists($key))
		{
			unset($_SESSION[$key]);
		}
	}
	
	/**
	 * Removes an array key variable from the Session
	 *
	 * @param string $key the key to remove
	 * @param string $arrayKey the key of the array remove
	 */
	function removeArrayKey($key, $arrayKey) {
		if($this->arrayKeyExists($key, $arrayKey))
		{
			unset($_SESSION[$key][$arrayKey]);
		}
	}
	
}
?>