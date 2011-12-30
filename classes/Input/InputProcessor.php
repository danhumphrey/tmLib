<?php
/**
  * InputProcessor class
  * 
  * @package tmLib
  * @subpackage Input
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */
/**
 * InputProcessor stores and processes input fields and handles 'state'
 * 
 * @package tmLib
 * @subpackage Input
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class InputProcessor implements IInputProcessor {

	Const STATE_INIT = 0;
	Const STATE_VALID = 1;
	Const STATE_INVALID = 2;
	
	private $triggerVar = '';
	private $triggerValue = '';
	private $fields = array();
	private $state;
	
	/**
	 * Constructs the InputProcessor
	 * @param object $stateListener an object implementing the ICommandState interface
	 * @param string $triggerVar (optional) the name of the variable that triggers the process
	 * @param string $triggerValue (optional) the value of the triggerVar variable that triggers the process
	 */
	function __construct(ICommandState $stateListener,$triggerVar='',$triggerValue='') {
		$this->triggerVar = $triggerVar;
		$this->triggerValue = $triggerValue;
		$this->state = self::STATE_INIT;
		$this->stateListener = $stateListener;
	}
	
	/**
	 * Loads a field definition file
	 * TODO: extract loading to external loader
	 * @todo extract loading to external loader
	 * @param $file the definition file to load
	 */
	function loadFieldDefinitions($file) {
		if(is_readable($file))
		{
			include($file);
			foreach ($def as $field)
			{
				$input = new InputField($field['name']);
				$filters = isset($field['filters']) ? $field['filters'] : array();
				if(is_array($filters)) {
					foreach ($field['filters'] as $filter) 
					{	
						$input->addFilter($filter);
					}
				}
				$rules = isset($field['rules']) ? $field['rules'] : array();
				if(is_array($rules)) {
					foreach ($rules as $rule) {
						$input->addRule($rule['ruleType'],$rule['processingCommand']);
					}
				}
				
				$this->addField($input);
			}
		}
	}
	/**
	 * Adds a field to the processor
	 *
	 * @param object IInputField $field
	 */
	function addField($field) {
		$this->fields[] = $field;
	}
	/**
	 * Returns an array of fields
	 *
	 * @return array the fields defined
	 */
	function getFields() {
		return $this->fields;	
	}
	
	/**
	 * Processes the data set against the defined fields
	 *
	 * @param object DataSet $dataSet
	 * @return boolean false if any process failed
	 */
	function process($dataSet) {
		$error = false;
		//requires a trigger to process?
		if($this->triggerVar != '')
		{
			//get trigger variable from input
			$trigger = $dataSet->get($this->triggerVar);
			if($trigger == '') {
				//trigger not set so set init state
				$this->stateListener->stateInitAction();
				return;
			}
			//requires specific trigger value?
			if($this->triggerValue != '') {
				if($trigger != $this->triggerValue)
				{
					//trigger value not correct, so set init state
					$this->stateListener->stateInitAction();
					return;
				}
			}
		}
		$this->state = self::STATE_VALID;
		//process fields
		foreach ($this->fields as $field) {
			if(!$field->process($dataSet)) {
				$error = true;
			}
		}
		if($error == true){
			$this->state = self::STATE_INVALID;
			$this->stateListener->stateInvalidAction();	
			return false;
		}
		$this->stateListener->stateValidAction();
		return true;
	}
	/**
	 * Returns the state
	 *
	 * @return int the state of the controller represented by a STATE constant
	 */
	function getState() {
		return $this->state;
	}
}
?>