<?php
/**
  * ConditionalRequiredRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * ConditionalRequiredRule class for ensuring that a required request variable was sent when a conditional field also has a value
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class ConditionalRequiredRule extends BaseRule
{
	private $conditionalField;
	
	/**
     * Constructs the ConditionalRequiredRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     * @param string $conditionalField - the field that must be set before this rule is processed
     */
	function __construct($errorMsg, $conditionalField){
		parent::__construct($errorMsg);
		$this->conditionalField = $conditionalField;
	}
	
	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)	{
		if($dataSet->get($this->conditionalField) !='') {
			if($dataSet->get($this->fieldName) =='')
			{
				$this->setError($dataSet);
				return false;
			}
		}
		return true;
	}
}
?>