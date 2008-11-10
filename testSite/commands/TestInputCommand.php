<?php
class TestInputCommand extends AuthorizedCommand implements ICommandState{
	protected $inputProcessor;
	
	function __construct() {
		//create an input processor that responds to the Submit button
		$this->inputProcessor = new InputProcessor($this,'Submit');
		//and add fields to the processor
		//-name
		$name = new InputField('name');
		$name->addFilter(new TrimFilter());
		$name->addFilter(new ConcatFilter('newField', array('field1', 'field2')));
		$name->addRule(new RequiredRule('the name is required'));
		$this->inputProcessor->addField($name);
	}
	
	function execute($request, $response) {
		if(parent::execute($request, $response)) {
			//process
			$this->inputProcessor->process($this->dataSet);
		}
	}
	
	function stateInitAction() {
		$this->response->setContent('TestInputCommand executed - init');
	}
	
	function stateValidAction() {
		$this->response->setContent('TestInputCommand executed - valid : name: "'.$this->dataSet->get('name').'"');
	}
	
	function stateInvalidAction() {
		$this->response->setContent('TestInputCommand executed - invalid :<br/>' . $this->dataSet->getArrayKey('errors','name'));
	}
}	
?>
