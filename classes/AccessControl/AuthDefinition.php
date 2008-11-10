<?php
/**
  * AuthDefinition class
  *
  * @package tmLib
  * @subpackage AccessControl
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * AuthDefinition Class defines the parameters required by the Auth Class
 *
 * @package tmLib
 * @subpackage AccessControl
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class AuthDefinition {
	
	private $loginVar;
	private $passwordVar;
	private $hashKey;
	private $retryCount;
	private $md5;
	/**
	 * Constructs the AuthDefinition
	 *
	 * @param string $loginVar the request variable name of the 'login' field
	 * @param string $passwordVar the request variable name of the 'password' field
	 * @param string $hashKey the secret hash key for data encryption 
	 * @param int $retryCount (optional) the number of invalid attempts before login is blocked
	 * @param bool $md5 (optional) whether the password is stored using md5 encryption
	 */
	function __construct($loginVar, $passwordVar, $hashKey,$retryCount=0,$md5=true) {
		$this->loginVar = $loginVar;
		$this->passwordVar = $passwordVar;
		$this->hashKey = $hashKey;
		$this->retryCount = $retryCount;
		$this->md5 = $md5;
	}
	/**
	 * Gets the login variable name
	 *
	 * @return string the request variable name of the 'login' field
	 */
	function getLoginVar() {
		return $this->loginVar;
	}
	/**
	 * Gets the password variable name
	 *
	 * @return string the request variable name of the 'password' field
	 */
	function getPasswordVar() {
		return $this->passwordVar;
	}
	/**
	 * Gets the hashKey
	 *
	 * @return string the secret hashKey
	 */
	function getHashKey() {
		return $this->hashKey;
	}
	/**
	 * Gets the retryCount
	 *
	 * @return int the max number of invalid logins
	 */
	function getRetryCount() {
		return $this->retryCount;
	}
	/**
	 * Gets the md5 encryption setting
	 *
	 * @return bool true if using md5
	 */
	function useMd5() {
		return $this->md5;
	}
}
?>