<?php
/**
  * PatternRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * PatternRule class for testing the value of a request variable against a regular expression.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class PatternRule extends BaseRule
{
	private $pattern;

	/**
     * Constructs the PatternRule class 
     * @access public
     * @param string $errorMsg the error message for when the Rule fails
     * @param string $pattern the regex pattern
     */
	function __construct($errorMsg, $pattern){
		parent::__construct($errorMsg);
		$this->pattern = $pattern;
	}

	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet){
		if($val = $dataSet->get($this->fieldName)) {
		// valid chars
    	if (!preg_match($this->pattern, $val)) {
    			$this->setError($dataSet);
				return false;
			}
		}

		return true;
	}
}
?>