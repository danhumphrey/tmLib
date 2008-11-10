<?php
/**
  * IRequestHandler Interface
  * 
  * @package tmLib
  * @subpackage RequestMappers
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * IRequestHandler should be implemented by all classes that 'handle' the request
 * 
 * @package tmLib
 * @subpackage RequestMappers
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
interface IRequestHandler{
	/**
	 * executes the handler
	 *
	 * @param object IDataSet $request
	 * @param object HttpResponse $response
	 */
	public function execute($request, $response);
}
?>