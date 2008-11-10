<?php
/**
  * HttpResponse class
  * 
  * @package tmLib
  * @subpackage Http
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * HttpResponse class for encalpsulating response content, headers and redirects.
 * 
 * @package tmLib
 * @subpackage Http
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class HttpResponse {
	private $headers = array();
	private $redirect = null;
	private $content = '';
	
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
	 * @param string $content the content
	 * 
	 */
	function setContent($content) {
		$this->content = $content;
	}
	/**
	 * Gets the content
	 * @access public
	 * @return string the content
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
			return $this->content;
		}
	}
}
?>