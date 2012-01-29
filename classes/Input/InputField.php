<?php
/**
  * InputField class
  * 
  * @package tmLib
  * @subpackage Input
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */
/**
 * InputField defines a data field expected in the user input. 
 * Also stores and processes filters and rules on the data.
 * 
 * @package tmLib
 * @subpackage Input
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class InputField implements IInputProcessor {
	
	private $fieldName;
	private $value;
	private $filters = array();
	private $rules = array();
	private $error = false;
	
	/**
     * Constructs the InputField class 
     * @access public
     * @param string $fieldName the field name of the request variable
     */
	function __construct($fieldName) {
		$this->fieldName = $fieldName;
	}
	/**
     * Adds a filter to the InputField 
     * @access public
     * @param object IInputProcessor $filter the filter object to add
     */
	function addFilter($filter) {
		if(method_exists($filter,'setFieldName')){
			//set the filter fieldName to this name
			$filter->setFieldName($this->fieldName);
		}
		$this->filters[] = $filter;
	}
	/**
     * Adds a rule to the InputField 
     * @access public
     * @param object IInputProcessor $rule the rule object to add
     * @param int $processingCommand (optional) whether rules processing should continue or stop on failure
     */
	function addRule($rule, $processingCommand = self::PROCESS_STOP) {
		try{
			//set the rule fieldName to this name
			$rule->setFieldName($this->fieldName);
		}
		catch(Exception $e){}
		
		$this->rules[] = array($rule, $processingCommand);
	}
	/**
	 * Returns the field Name
	 * @return string the name of the field
	 */
	function getName() {
		return $this->fieldName;
	}
	/**
	 * Sets the field Value
	 * @param mixed $value the field value to set
	 */
	function setValue($value) {
		$this->value = $value;
	}
	/**
	 * Returns the field Value
	 * @return mixed the value of the field
	 */
	function getValue() {
		return $this->value;
	}
	/**
	 * Returns the filters
	 * @return array an array of filters
	 */
	function getFilters() {
		return $this->filters;
	}
	/**
	 * Returns the rules and associated processing command
	 * @return array an array of rules and processing commands
	 */
	function getRules() {
		return $this->rules;
	}
	/**
	 * Processes the input field against a dataset
	 *
	 * @param object DataSet $dataSet
	 * @return bool false if error occured
	 */
	function process($dataSet) {
		//process filters
		foreach ($this->getFilters() as $filter) {
			$filter->process($dataSet);
		}
		$this->setValue($dataSet->get($this->fieldName));
		//set field value in dataset
		$dataSet->set($this->fieldName, $this->value);
		//process rules
		foreach ($this->getRules() as $rule) {
			if($rule[0]->process($dataSet) == false) {
				$this->error = true;
				//stop processing further rules?
				if($rule[1] == self::PROCESS_STOP) {
					return false; //return false 
				}
			}
		}
		//return true if no errors
		if($this->error == false){ return true; }
	}
}
?>