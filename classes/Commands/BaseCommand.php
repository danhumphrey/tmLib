<?php
/**
  * BaseCommand class
  * 
  * @package tmLib
  * @subpackage Commands
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * BaseCommand class should be extended by all other command classes, it 'holds' the request and
 * response, and creates a dataset to work with
 * 
 * @package tmLib
 * @subpackage Commands
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @abstract 
 * @version 1.0
 */
abstract class BaseCommand implements IRequestHandler {
	/**
	 * The base HttpRequest object
	 *
	 * @var HttpRequest
	 */
	protected $request;
	/**
	 * The base HttpResponse Object
	 *
	 * @var HttpResponse
	 */
	protected $response;
	/**
	 * The dataSet which will be populated with the HttpRequest vars and used to manipulate data leaving the request untouched
	 *
	 * @var DataSet
	 */	
	protected $dataSet;
	
	/**
	 * Executes the command if canExecute returns true and creates a dataset for the command to work with.
	 * Must be called by children who override this class - eg. __parent::execute($request, $response)
	 *
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 * @return bool true if execute can proceed
	 */
	function execute($request, $response) {
		$this->request = $request;
		$this->response = $response;
		//create a dataSet to use (leaving $request untouched)
		$this->dataSet = new DataSet($request->getData());
		return $this->canExecute();
	}
	
	/**
	 * Can Execute can be overridden by children to verify that execution can proceed 
	 * @return bool true if execution can proceed
	 */
	protected function canExecute(){
		return true;
	}
}	
?>
