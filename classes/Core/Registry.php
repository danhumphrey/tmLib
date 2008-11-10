<?php
/**
  * Registry class
  * 
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * Registry class for holding static references to objects.
 * 
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class Registry{
	protected static $store = array();
	/**
	 * Checks if a key exists in the registry
	 * @access public
	 * @param string $key the name to check
	 * @return boolean true if a key named $key exists in the registry
	 */
	static function keyExists($key) {
		return array_key_exists($key, Registry::$store);
	}
	/**
	 * Returns reference to an object with the key $key 
	 * @access public
	 * @param string $key the identifier of the object to retrieve
	 * @return mixed the the object associated with the key $key or null if none exists
	 */
	static function get($key) {
		if (array_key_exists($key, Registry::$store)) {
			return Registry::$store[$key];
		}
	}
	/**
	 * Adds a reference to an object
	 * @access public
	 * @param string $key the identifier of the object
	 * @param mixed $value the value of the object
	 */
	static function set($key, $obj) {
		Registry::$store[$key] = $obj;
	}
	
}
?>