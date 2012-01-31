<?php
/**
  * OneRequiredRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * OneRequiredRule class for ensuring that one of a list of required request variables was sent.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class OneRequiredRule extends BaseRule
{
	private $fields;
		
	/**
     * Constructs the OneRequiredRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     * @param array $fields an array of fields to compare (automatically includes the field that the rule was added to)
     */
	function __construct($errorMsg, array $fields)
	{
		parent::__construct($errorMsg);
		$this->fields = $fields;
	}
	
	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)
	{
		$this->fields[]= $this->fieldName;
		foreach($this->fields as $field)
		{
			$val = $dataSet->get($field);
			if(isset($val))
			{
				return true;
			}
			
		}
		$this->setError($dataSet);
		return false;
	}
}
?>