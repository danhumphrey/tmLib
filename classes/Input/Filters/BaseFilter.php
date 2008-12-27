<?php
/**
  * BaseFilter class
  * 
  * @package tmLib
  * @subpackage InputFilters
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * BaseFilter class for filtering request variables.
 * 
 * @package tmLib
 * @subpackage InputFilters
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @abstract
 * @version 1.0
 */
abstract class BaseFilter implements IInputProcessor{
	protected $fieldName;
	/**
     * Constructs the BaseFilter class 
     * @access public
     * @param string $fieldName (optional)  field name of the request variable that the filter will process
     */
	function __construct(){
	}
	
	/**
	 * Sets the field name of the request variable that the filter will process
	 * @access public
	 * @param string $fieldName the field name of the request variable that the filter will process
	 */
	function setFieldName($fieldName) {
		$this->fieldName = $fieldName;
	}
}
?>