<?php
/**
  * IDataSet interface
  *
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */
/**
 * IDataSet provides a common interface to data storage and retrieval
 *
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
interface IDataSet {
	/**
	 * Returns a variable from the dataset if it exists
	 * @access public
	 * @param string $key the name of the variable to retrieve
	 * @return mixed the value of the variable or null if the variable does not exist
	 */
	function get($key);
	/**
	 * Returns an array key variable from the dataset if it exists
	 * @access public
	 * @param string $key the key to the array
	 * @param string $arrayKey the key of the array variable to retrieve
	 * @return mixed the value of the array variable or null if the variable does not exist
	 */
	function getArrayKey($key, $arrayKey);
	/**
	 * Returns the dataset as an array
	 * @access public
	 * @return array the entire dataset
	 */
	function getData();
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
	function keyExists($key);
	
	/**
	 * Checks if an array key exists in the dataset
	 * @access public
	 * @param string $key the key of the array in the dataset
	 * @param string $arrayKey the key of the array
	 * @return boolean true if a variable named $key exists in the request
	 */
	function arrayKeyExists($key,$arrayKey);
	/**
	 * Sets a key and value pair in the dataset
	 *
	 * @param string $key the key name
	 * @param mixed $value the value of the key
	 */
	function set($key, $value);
	
	/**
	 * Sets an array key
	 *
	 * @param string $key the key of the array in the dataset
	 * @param string $arrayKey the key to set within the array 
	 * @param mixed $value the value of the array key
	 */
	function setArrayKey($key, $arrayKey, $value);
	/**
	 * Removes a variable from the dataset
	 *
	 * @param string $key the key to remove
	 */
	function remove($key);
	
	/**
	 * Removes an array key variable from the Session
	 *
	 * @param string $key the key to the array
	 * @param string $arrayKey the key of the array remove
	 * 
	 */
	function removeArrayKey($key, $arrayKey);
}	
?>