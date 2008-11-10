<?php
/**
  * HttpAjaxResponse class
  * 
  * @package tmLib
  * @subpackage Http
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * HttpAjaxResponse class for encalpsulating ajax response content, headers and redirects.
 * 
 * @package tmLib
 * @subpackage Http
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class HttpAjaxResponse {
	private $headers = array();
	private $redirect = null;
	private $content = array();
	
	/**
	 * Adds a Http header to the headers array
	 * @access public
	 * @param string $header the header to set
	 * 
	 */
	function setHeader($header) {
		$this->headers[] = $header;
	}
	/**
	 * Gets an array of the headers
	 * @access public
	 * @return array an array of headers
	 */
	function getHeaders() {
		return $this->headers;
	}
	/**
	 * Sets the redirect url
	 * @access public
	 * @param string $url the url to redirect to
	 * 
	 */
	function setRedirect($url) {
		$this->redirect = $url;
	}
	/**
	 * Gets the redirect value
	 * @access public
	 * @return the redirect url
	 */
	function getRedirect() {
		return $this->redirect;
	}
	/**
	 * Sets the content
	 * @access public
	 * @param array $content the content
	 * 
	 */
	function setContent(array $content) {
		$this->content = $content;
	}
	
	/**
	 * Sets a key, value pair in the response
	 *
	 * @param string $name the name of the key
	 * @param string $value the value of the key
	 */
	function setKey($name, $value) {
		$this->content[$name] = $value;
	}
	
	/**
	 * Returns a key from the content array
	 *
	 * @param string $name the name of the key to return
	 * @return mixed the value of the key
	 */
	function getKey($name) {
		if(array_key_exists($name,$this->content))
		{
			return $this->content[$name];
		}
	}
	
	/**
	 * Gets the content
	 * @access public
	 * @return array the content
	 */
	function getContent() {
		return $this->content;
	}
	/**
	 * Either redirects if $this->redirect has been set or
	 * returns the content 
	 *
	 * @return returns content if no redirect header is set
	 */
	function execute() {
		if ($this->redirect) {
			header('Location: ' . $this->redirect);
		}else {
			foreach ($this->headers as $header) {
				header($header);
			}
			return json_encode($this->content);
		}
	}
}
?>