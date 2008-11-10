<?php
class Test1Command extends BaseCommand  {
	
	function __construct() 
	{
		$this->inputProcessor = new InputProcessor($this,'Submit','doIt');
		 //..and add some fields
		/*
		$name = new InputField('forename');
		$name->addFilter(new TrimFilter('forename'));
		$name->addRule(new RequiredRule('forename','forename is required'),InputField::PROCESS_STOP);
		$name->addRule(new LengthRule('forename','forename should be between 2 and 20 chars',2,20));
		$this->inputProcessor->addField($name);
		
		$surname = new InputField('surname');
		$surname->addFilter(new TrimFilter('surname'));
		$surname->addRule(new RequiredRule('surname','surname is required'),InputField::PROCESS_STOP);
		$this->inputProcessor->addField($surname);
		*/
		
		//..load a field definition file
		$this->inputProcessor->loadFieldDefinitions('FieldDefinitions/Test1Fields.php');
	}
	
	function execute($request,$response)
	{
		parent::execute($request, $response);
	}
	function stateInitAction() {
		//initialise
		$this->showForm($this->response);
	}
	function stateInvalidAction() {
		//invalid
		$this->dataSet->set('pageMsg','error');
		$this->showForm($this->response);
	}
	
	function stateValidAction() {
		//execute model
		$this->dataSet->set('pageMsg','success');
		$page = new ServerPage('pages/Success.php');
		$page->execute($this->request, $this->response);
		//$this->response->setContent('<h2>Success!</h2>Thanks, ' . $this->dataSet->get('forename').'.');
	}

	private function showForm($response) {
		$response->setContent( '		
		<p>' . $this->dataSet->get('pageMsg') . '</p>
<form id="form1" name="form1" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <input type="hidden" name="cmd" value="' . $this->dataSet->get('cmd') . '"/>
  <p>Forename:
    <input name="forename" type="text" id="forename" value="' . $this->dataSet->get('forename') .'" /> 
    '. $this->dataSet->get('forenameError') .'</p>
<p>Surname:
<input name="surname" type="text" id="surname" value="' . $this->dataSet->get('surname') .'" />
'. $this->dataSet->get('surnameError') .'
</p>
  <p><input name="Submit" type="submit" id="Submit" value="doIt" /></p>
</form>
<p>&nbsp; </p>');
	}
}
?>
