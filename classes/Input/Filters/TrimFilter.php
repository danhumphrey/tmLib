<?php
/**
  * TrimFilter class
  * 
  * @package tmLib
  * @subpackage InputFilters
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * TrimFilter class for trimming the value of a request variable.
 * 
 * @package tmLib
 * @subpackage InputFilters
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class TrimFilter extends BaseFilter{

	/**
     * Constructs the TrimFilter class 
     * @access public
     * @param string $fieldName (optional) the field name of the request variable
     */
	function __construct($fieldName=null){
		parent::__construct($fieldName);
	}
	
	/**
	 * Processes the filter and sets the dataset value
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet) {
		$dataSet->set($this->fieldName, trim($dataSet->get($this->fieldName)));
	}
}
?>