<?php
/**
  * ComparisonRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * ComparisonRule class for comparing the value of 2 request variables.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class ComparisonRule extends BaseRule{
	private $comparisonField;

	/**
     * Constructs the ComparisonRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     * @param string $comparisonField the field name of the request variable to compare with
     */
	function __construct($errorMsg, $comparisonField){
		parent::__construct($errorMsg);
		$this->comparisonField = $comparisonField;
	}

	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)	{
		if($val = $dataSet->get($this->fieldName)) {
			if($val != $dataSet->get($this->comparisonField)){
				$dataSet->set($this->fieldName . 'Error', $this->errorMsg);
				$dataSet->setArrayKey('errors',$this->fieldName,$this->errorMsg);
				return false;
			}
		}
		return true;
	}
}
?>
