<?php
/**
  * ConcatFilter class
  * 
  * @package tmLib
  * @subpackage InputFilters
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * ConcatFilter class for concatenating request variables.
 * 
 * @package tmLib
 * @subpackage InputFilters
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class ConcatFilter extends BaseFilter{
	private $fields;
	private $newFieldName;
	private $concatenator;
	/**
     * Constructs the ConcatFilter class 
     * @access public
     * @param string $fieldName (optional) the field name of the request variable to populate with the concatenation. 
     * This request variable usually wouldn't exist in a http request.
     * @param array $fields the existing fields in the order of concatenation
     * @param string $concatenator (optional) a token to concatenate between each field
     */
	function __construct($fieldName=null, array $fields, $concatenator = ''){
		parent::__construct($fieldName);
		$this->fields = $fields;
		$this->concatenator = $concatenator;
	}		
	/**
	 * Processes the filter and sets the dataset value
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet) {
		$val = '';
		$out = array();
		foreach ($this->fields as $field) {
			$out[] = $dataSet->get($field);
		}
		$dataSet->set($this->fieldName, join($this->concatenator, $out));
	}
}
?>