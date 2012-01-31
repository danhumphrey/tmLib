<?php
/**
  * LengthRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * LengthRule class for comparing the string length of a request variable.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class LengthRule extends BaseRule{

	private $min;
	private $max;
	
	/**
     * Constructs the LengthRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     * @param string $min (optional) the min length of the variable
     * @param string $max (optional) the max length of the variable
     */
	function __construct($errorMsg,$min=null,$max=null)
	{
		parent::__construct($errorMsg);
		$this->min = $min;
		$this->max = $max;
	}
	
	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)
	{  
		if($val = $dataSet->get($this->fieldName)) {

			if(!$this->min == null) {
				if(strlen($val) < $this->min)
				{
					$this->setError($dataSet);
					return false;
				}
			}
			if(!$this->max == null) {
				if(strlen($val) > $this->max)
				{
					$this->setError($dataSet);
					return false;
				}
			}
		}
		return true;
	}
}
?>