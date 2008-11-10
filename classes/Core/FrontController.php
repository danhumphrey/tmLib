<?php
/**
  * FrontController class
  *
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * FrontController class for mapping and dispatching request commands to a RequestHandler
 *
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class FrontController
{
	private $mapper;
	
    /**
     * Constructs the FrontController
     *
     * @param BaseRequestMapper $mapper a request mapper that extends BaseRequestMapper
     */
    function FrontController($mapper) {
    	$this->mapper = $mapper;
    }

    /**
     * Executes the FrontController
     *
     * @param HttpRequest $request a HttpRequest object
     * @param HttpResponse $response a HttpResponse object
     * @return bool whether execution was successful
     */
    function execute($request, $response) {
    	if($dispatch = $this->mapper->mapRequest($request)){
	    	if (method_exists($dispatch, 'execute')) {
	    		$dispatch->execute($request, $response);
	    		return true;
	    	}
    	}
    }
}
?>