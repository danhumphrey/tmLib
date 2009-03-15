<?php
/**
  * LowerCaseFilter class
  * 
  * @package tmLib
  * @subpackage InputFilters
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * LowerCaseFilter class for ensuring that a request variable is lower case.
 * 
 * @package tmLib
 * @subpackage InputFilters
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class LowerCaseFilter extends BaseFilter{

	/**
     * Constructs the LowerCaseFilter class 
     * @access public
     * @param string $fieldName (optional) the field name of the request variable
     */
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Processes the filter and sets the dataset value
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet) {
		$dataSet->set($this->fieldName, strtolower($dataSet->get($this->fieldName)));
	}
}
?>