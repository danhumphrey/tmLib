<?php
/**
  * AllowedValuesRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * AllowedValuesRule class for testing the value of a request variable is in an allowed list.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class AllowedValuesRule extends BaseRule {

	private $allowed;

	/**
     * Constructs the AllowedValuesRule class 
     * @access public
     * @param string $errorMsg the error message for when the Rule fails
     * @param array $allowed the regex pattern
     */
	function __construct($errorMsg,array $allowed){
		parent::__construct($errorMsg);
		$this->allowed = $allowed;
	}

	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)
	{
		if($val = $dataSet->get($this->fieldName)) {
		// valid chars
    	if (!in_array($val, $this->allowed)) {
    			$dataSet->set($this->fieldName . 'Error', $this->errorMsg);
    			$dataSet->setArrayKey('errors',$this->fieldName,$this->errorMsg);
				return false;
			}
		}

		return true;
	}
}
?>