<?php
/**
  * RequiredRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * RequiredRule class for ensuring that a required request variable was sent.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class RequiredRule extends BaseRule
{
	
	/**
     * Constructs the RequiredRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     */
	function __construct($errorMsg){
		parent::__construct($errorMsg);
	}
	
	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)
	{
		$val = $dataSet->get($this->fieldName);
		if(!isset($val))
		{
			$this->setError($dataSet);
			return false;
		}
		return true;
	}
}
?>