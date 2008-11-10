<?php
/**
  * StripTagsFilter class
  * 
  * @package tmLib
  * @subpackage InputFilters
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * StripTagsFilter class for stripping html tags from a request variable.
 * 
 * @package tmLib
 * @subpackage InputFilters
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class StripTagsFilter extends BaseFilter {

	/**
     * Constructs the StripTagsFilter class 
     * @access public
     * @param string $fieldName the field name of the request variable
     */
	function __construct($fieldName){
		parent::__construct($fieldName);
	}
	
	/**
	 * Processes the filter and sets the dataset value
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet) {
		$dataSet->set($this->fieldName, strip_tags($dataSet->get($this->fieldName)));
	}
}
?>