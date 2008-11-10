<?php
if (! defined('SIMPLE_TEST')) {
	define('SIMPLE_TEST', 'C:\\web\\simpletest\\');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

//main tmLib includes file
require_once('../includes.php');

class TestInputCommand extends BaseCommand implements ICommandState{
	protected $inputProcessor;
	
	function __construct($ip) {
		//create an input processor that responds to the default Submit action
		$this->inputProcessor = $ip;
	}
	function execute($request, $response) {
		if(parent::execute($request, $response)) {
			//process
			$this->inputProcessor->process($this->dataSet);
		}
	}
	function canExecute() {
		return true;
	}
	
	function stateInitAction(){}
	function stateValidAction(){}
	function stateInvalidAction(){}
}

Mock::generate('DataSet');
Mock::generate('InputField');
Mock::generate('TestInputCommand');

class TestOfInputProcessor extends UnitTestCase {
	private $ds;
	private $cmd;
	private $fieldDefFile;
	private $fieldDefPath;
	
	function setUp()
	{
		$this->cmd = new MockTestInputCommand();
		$this->ds = new MockDataSet();
		$this->fieldDefPath = SITE_PATH.DIRSEP.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'FieldDefinitions'.DIRSEP;
		if(!file_exists($this->fieldDefPath)){
			mkdir($this->fieldDefPath);
		}
		$this->fieldDefFile = $this->fieldDefPath . 'testDef.php';
		$this->createDefFile();
	}
	function __construct() {

		
		parent::__construct();
	}
	
	function createDefFile() {
		/*
		$out = '$def'."=array \n";
		$out.= "( \n";
		$out.= "	'field1'=>array \n";
		$out.= "	( \n";
		$out.= "		'name'=>'field1', \n";
		$out.= "		'filters'=>array \n";
		$out.= "		( \n";
		$out.= "		), \n";
		$out.= "		'rules'=>array \n";
		$out.= "		( \n";
		$out.= "			array \n";
		$out.= "			( \n";
		$out.= "				'ruleType'=>new RequiredRule('field1','field2 is required'), \n";
		$out.= "				'processingCommand'=>IInputProcessor::PROCESS_STOP \n";
		$out.= "			), \n";
		$out.= "			array \n";
		$out.= "			( \n";
		$out.= "				'ruleType'=>new LengthRule('field1','field2 should be 10 chars or less',0,10), \n";
		$out.= "				'processingCommand'=>IInputProcessor::PROCESS_CONTINUE \n";
		$out.= "			) \n";
		$out.= "		), \n";
		$out.= "	), \n";
		$out.= "	'field2'=>array \n";
		$out.= "	( \n";
		$out.= "		'name'=>'field2', \n";
		$out.= "		'filters'=>array \n";
		$out.= "		( \n";
		$out.= "			new TrimFilter('field2') \n";
		$out.= "		), \n";
		$out.= "		'rules'=>array \n";
		$out.= "		( \n";
		$out.= "			array \n";
		$out.= "			( \n";
		$out.= "				'ruleType'=>new RequiredRule('field2','field2 is required'), \n";
		$out.= "				'processingCommand'=>IInputProcessor::PROCESS_CONTINUE \n";
		$out.= "			) \n";
		$out.= "		) \n";
		$out.= "	) \n";
		$out.= "); \n"; 
		*/
		$out = '$def[\'field1\'][\'name\'] = \'forename\';' ."\n";
		$out.= '$def[\'field1\'][\'filters\'] = array(new TrimFilter(\'forename\'));' ."\n";
		$out.= '$def[\'field1\'][\'rules\'][0][\'ruleType\'] = new RequiredRule(\'forename\',\'forename is required\');' ."\n";
		$out.= '$def[\'field1\'][\'rules\'][0][\'processingCommand\']= IInputProcessor::PROCESS_STOP;' ."\n";
		$out.= '$def[\'field1\'][\'rules\'][1][\'ruleType\'] = new LengthRule(\'forename\',\'forename should be between 2 and 20 chars\',2,20);' ."\n";
		$out.= '$def[\'field1\'][\'rules\'][1][\'processingCommand\']= IInputProcessor::PROCESS_STOP;' ."\n";
		
		$out.= '$def[\'field2\'][\'name\'] = \'surname\';' ."\n";
		$out.= '$def[\'field2\'][\'filters\'] = array(new TrimFilter(\'surname\'));' ."\n";
		$out.= '$def[\'field2\'][\'rules\'][0][\'ruleType\'] = new LengthRule(\'surname\',\'surname should be between 5 and 20 chars\',5,20);' ."\n";
		$out.= '$def[\'field2\'][\'rules\'][0][\'processingCommand\'] = IInputProcessor::PROCESS_STOP;' ."\n";
		
		$file = fopen($this->fieldDefFile,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, $out);
		fwrite($file, '?>'. "\n");
		fclose($file);
	}
	function tearDown() {

		if(file_exists($this->fieldDefFile)) {
			unlink($this->fieldDefFile);
		}		
		if(file_exists($this->fieldDefPath)) {
			rmdir($this->fieldDefPath);
		}
		unset($this->ds);
	}


	function testAddAndGetFields() {
		$processor = new InputProcessor($this->cmd);
		$input = new MockInputField('input');
		$input2 = new MockInputField('input2');
		$processor->addField($input);
		$processor->addField($input2);
		$this->assertEqual($processor->getFields(), array($input, $input2));
	}

	function testLoadFields() {
		
		$processor = new InputProcessor($this->cmd);
		$processor->loadFieldDefinitions($this->fieldDefFile);
		$fields = $processor->getFields();
	
		//generic tests
		$this->assertIsA($fields,'array','expecting fields to be an array');
		foreach ($fields as $field)
		{
			$this->assertIsA($field,'InputField','each field should be of type InputField');
			foreach ($field->getFilters() as $filter)
			{
				$this->assertIsA($filter,'IInputProcessor','each filter should be of type IInputProcessor');
			}
			foreach ($field->getRules() as $rule)
			{
				$this->assertIsA($rule[0],'IInputProcessor','each rule should be of type IInputProcessor');
			}
		}
		//tests specific to the field def
		$field1 = $fields[0];
		$this->assertEqual($field1->getName(),'forename', "field should be named 'forename'");
		$this->assertEqual(sizeof($field1->getFilters()),1,'field1 should have 1 filter');
		$this->assertEqual(sizeof($field1->getRules()),2,'field1 should have 2 rules');
		
		$field2 = $fields[1];
		$this->assertEqual($field2->getName(),'surname', "field should be named 'surname'");
		$this->assertEqual(sizeof($field2->getFilters()),1,'field2 should have 1 filter');
		$this->assertEqual(sizeof($field2->getRules()),1,'field2 should have 1 rule');
	}
	function testFieldsProcessedWithBlankTrigger() {
		
		$processor = new InputProcessor($this->cmd);
		$processor->loadFieldDefinitions($this->fieldDefFile);
		$this->ds->setReturnValue('get', 'DoIt', array('Submit'));
		$input = new MockInputField();
		$input->expectOnce('process', array($this->ds),'input should be processed');
		$input2 = new MockInputField();
		$input2->expectOnce('process', array($this->ds),'input 2 should be processed');
		$processor->addField($input);
		$processor->addField($input2);
		$processor->process($this->ds);
		$input->tally();
		$input2->tally();
	}
	function testFieldsProcessedWithCustomTriggerAndValue() {
		$processor = new InputProcessor($this->cmd,'button','doIt');
		$this->ds->setReturnValue('get', 'doIt', array('button'));
		$input = new MockInputField();
		$input->expectOnce('process', array($this->ds),'input should be processed');
		$input2 = new MockInputField();
		$input2->expectOnce('process', array($this->ds),'input 2 should be processed');
		$processor->addField($input);
		$processor->addField($input2);
		$processor->process($this->ds);
		$input->tally();
		$input2->tally();
	}
	
	function testProcessCallsStateInit() {
		$this->cmd->expectOnce('stateValidAction');
		$processor = new InputProcessor($this->cmd);
		$processor->process($this->ds);
		$this->cmd->tally();
		
	}
	function testProcessCallsStateValid() {
		$this->cmd->expectOnce('stateValidAction');
		$processor = new InputProcessor($this->cmd,'Submit');
		$input = new MockInputField();
		$input->setReturnValue('process', true);
		$processor->addField($input);
		$this->ds->setReturnValue('get', 'DoIt', array('Submit'));
		$processor->process($this->ds);
		$this->cmd->tally();
	}
	function testProcessCallsStateInvalid() {
		$this->cmd->expectOnce('stateInvalidAction');
		$processor = new InputProcessor($this->cmd,'Submit');
		$input = new MockInputField();
		$input->setReturnValue('process', false);
		$processor->addField($input);
		$this->ds->setReturnValue('get', 'DoIt', array('Submit'));
		$processor->process($this->ds);
		$this->cmd->tally();
	}
	
	
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfInputProcessor');
	$test->addTestCase(new TestOfInputProcessor);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>