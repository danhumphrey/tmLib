<?php
/**
  * Dispatcher class
  *
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * Dispatcher class for mapping and dispatching request commands to a RequestHandler
 *
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class Dispatcher
{
	private $mappers = array();
	
    /**
     * Constructs the Dispatcher
     *
     * @param BaseRequestMapper $mapper a request mapper that extends BaseRequestMapper. Multiple mapper arguments can be passed to provide a chain of mappers.
     */
    function Dispatcher($mapper) {
    	for ($i = 0;$i < func_num_args();$i++) {
    		$this->mappers[] = func_get_arg($i);
    	}
    }

    /**
     * Executes the Dispatcher
     *
     * @param HttpRequest $request a HttpRequest object
     * @param HttpResponse $response a HttpResponse object
     * @return bool whether execution was successful
     */
    function execute($request, $response) {
    	foreach($this->mappers as $mapper) {
	    	if($dispatch = $mapper->mapRequest($request)){
		    	if (method_exists($dispatch, 'execute')) {
		    		$dispatch->execute($request, $response);
		    		return true;
		    	}
	    	}
    	}
    }
}
?>