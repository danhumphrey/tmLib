<?php
/**
  * DataSet class
  *
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * DataSet class provides a common interface to storage and retrieval of data
 *
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class DataSet implements IDataSet, ArrayAccess 
{
	protected $data = array();

	/**
	 * DataSet class for storing data and providing a common interface to that data
	 * @access public
	 */
	function __construct($data=array())
	{
		$this->data = $data;
	}

	/**
	 * Returns a variable from the dataset if it exists
	 * @access public
	 * @param string $key the name of the variable to retrieve
	 * @param mixed $default the default value to return when the key is not in the dataset
	 * @return mixed the value of the variable named $key or null if the variable
	 */
	function get($key,$default = null) {
	   return $this->keyExists($key) ? $this->data[$key] : $default;
	}
	
	/**
	 * Returns an array key variable from the dataset if it exists
	 * @access public
	 * @param string $key the key to the array
	 * @param string $arrayKey the key of the array variable to retrieve
	 * @return mixed the value of the array variable or null if the variable does not exist
	 */
	function getArrayKey($key, $arrayKey) {
		return $this->arrayKeyExists($key, $arrayKey) ? $this->data[$key][$arrayKey] : null;
	}
	
	/**
	 * Returns the dataset as an array
	 * @access public
	 * @return array the entire dataset
	 */
	function getData() {
		return $this->data;
	}
	/**
	 * Sets a variable in the dataset with the name $key and value $value
	 * @access public
	 * @param string $key the name of the variable
	 * @param mixed $value the value of the variable
	 */
	
	 /**
	 * Checks if a key exists in the dataset
	 * @access public
	 * @param string $key the name of the variable to check
	 * @return boolean true if a variable named $key exists in the request
	 */
	function keyExists($key) {
		return array_key_exists($key, $this->data);
	}
	/**
	 * Checks if an array key exists in a dataset array variable
	 * @access public
	 * @param string $key the key of the array in the dataset
	 * @param string $arrayKey the key of the array
	 * @return boolean true if a variable named $key exists in the request
	 */
	function arrayKeyExists($key,$arrayKey) {
		if(is_array($this->data[$key]))
		{
			return array_key_exists($arrayKey, $this->data[$key]);
		}
	}
	/**
	 * Sets a key and value pair in the dataset
	 *
	 * @param string $key the key name
	 * @param mixed $value the value of the key
	 */
	function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	/**
	 * Sets an array key
	 *
	 * @param string $key the key of the array in the dataset
	 * @param string $arrayKey the key to set within the array 
	 * @param mixed $value the value of the array key
	 */
	function setArrayKey($key, $arrayKey, $value)
	{
		$this->data[$key][$arrayKey] = $value;
	}
	/**
	 * Removes a variable from the dataset
	 *
	 * @param string $key the key to remove
	 */
	function remove($key) {
		if(array_key_exists($key, $this->data))
		{
			unset($this->data[$key]);
		}
	}
	
	/**
	 * Removes an array key variable from the dataset
	 *
	 * @param string $key the key to remove
	 * @param string $arrayKey the key of the array remove
	 */
	function removeArrayKey($key, $arrayKey) {
		if(array_key_exists($key, $this->data))
		{
			if(array_key_exists($arrayKey, $this->data[$key]))
			unset($this->data[$key][$arrayKey]);
		}
	}
	
	/**
	 * Array access function
	 *
	 * @access private
	 */
	function offsetGet($offset) {
		if($this->offsetExists($offset)) {
			return $this->data[$offset];	
		}
	}
	/**
	 * Array access function
	 *
	 * @access private
	 */
	function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}
	/**
	 * Array access function
	 *
	 * @access private
	 */
	function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}
	/**
	 * Array access function
	 *
	 * @access private
	 */
	function offsetUnset($offset) {
		if($this->offsetExists($offset)) {
			unset($this->data[$offset]);
		}
	}
}
?>