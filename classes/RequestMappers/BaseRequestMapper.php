<?php
/**
  * BaseRequestMapper class
  * 
  * @package tmLib
  * @subpackage RequestMappers
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * BaseRequestMapper should be extended by all 'Request Mappers'
 * 
 * @package tmLib
 * @subpackage RequestMappers
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 * @abstract 
 */
abstract class BaseRequestMapper {
	
	/**
	 * Maps a request to a class implementing IRequestHandler
	 *
	 * @param object HttpRequest $request
	 */
	abstract public function mapRequest($request);
	
	
	/**
	 * Untaints a request variable to prevent malicious input
	 *
	 * @param string $var the variable to untaint
	 * @return string the cleaned variable
	 */
	protected function untaint($var) {
		return preg_replace(array('/^[^a-zA-Z0-9]*/', '/[^a-zA-Z0-9]*$/', '/[^a-zA-Z0-9\_\/]/'), array(''), $var);
	}
}
?>