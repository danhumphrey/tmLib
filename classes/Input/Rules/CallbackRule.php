<?php
/**
  * CallbackRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * CallbackRule class for passing a request variable to a user function for validation.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class CallbackRule extends BaseRule{
	private $callback;

	/**
     * Constructs the CallbackRule class 
     * @access public
     * @param string $fieldName the field name of the request variable
     * @param string $errorMsg the error message for when comparison fails
     * @param mixed $callback the name of the callback function, or an array with a classname and the name of a static function. 
     */
	function __construct($errorMsg, $callback)
	{
		parent::__construct($errorMsg);
		$this->callback = $callback;
	}

	/**
	 * Processes the rule and sets the error in the dataset if the rule was not satisfied
	 * @access public
	 * @param object DataSet $dataSet the context data space for getting and setting data
	 */
	function process($dataSet)
	{
		if($val = $dataSet->get($this->fieldName)) {
			if(is_callable($this->callback))
			{
				if(!call_user_func($this->callback, $val))
				{
					$dataSet->set($this->fieldName . 'Error', $this->errorMsg);
					$dataSet->setArrayKey('errors',$this->fieldName,$this->errorMsg);
					return false;
				}
			}
		}
		return true;
	}
}
?>