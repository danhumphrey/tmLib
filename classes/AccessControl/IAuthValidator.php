<?php
/**
  * IAuthValidator interface
  *
  * @package tmLib
  * @subpackage AccessControl
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */
/**
 * IAuthValidator interface should be extended by all Auth Validators
 *
 * @package tmLib
 * @subpackage AccessControl
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
interface IAuthValidator {
	/**
	 * Validate the authorization usually by taking the login and password variable
	 * and querying a database to determin if correct.
	 *
	 * @param HttpRequest $request the HTTPRequest
	 * @param AuthDefinition $definition an AuthDefinition object
	 */
	function validateAuth($request, $definition);
}
?>