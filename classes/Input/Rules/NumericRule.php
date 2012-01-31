<?php
/**
  * NumericRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * NumericRule class for ensuring that a request variable is numeric.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class NumericRule extends BaseRule{

	
	/**
     * Constructs the NumericRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     */
	function __construct($errorMsg)
	{
		parent::__construct($errorMsg);
	}
	
	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)
	{
		if($val = $dataSet->get($this->fieldName))
		{
			if (!is_numeric($val)) {
				$this->setError($dataSet);
				return false;
			}
		}
		return true;
	}
}
?>