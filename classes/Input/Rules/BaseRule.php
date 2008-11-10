<?php
/**
  * BaseRule class
  * 
  * @package tmLib
  * @subpackage InputRules
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * BaseRule class for applying rules to validate request variables.
 * 
 * @package tmLib
 * @subpackage InputRules
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @abstract
 * @version 1.0
 */
abstract class BaseRule implements IInputProcessor
{
	protected $fieldName;
	protected $errorMsg;
	
	/**
     * Constructs the BaseRule class 
     * @access public
     * @param string $errorMsg the error message for when comparison fails
     */
	function __construct($errorMsg) {
		$this->errorMsg = $errorMsg;
	}
	
	/**
	 * Sets the field name of the request variable that the rule will apply to
	 * @access public
	 * @param string $fieldName the field name of the request variable that the rule will apply to
	 */
	function setFieldName($fieldName){
		$this->fieldName = $fieldName;	
	}
}
?>