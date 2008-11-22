<?php
//tests includes
require_once('test-includes.php');


Mock::generate('DataSet');
Mock::generate('RequiredRule');
Mock::generate('LengthRule');
Mock::generate('TrimFilter');

class TestOfInputField extends UnitTestCase {
	private $ds;
	function setUp()
	{
		$this->ds = new MockDataSet();
	}

	function tearDown() {
		unset($this->ds);
	}
	function __construct() {
		parent::__construct();
	}
	function testSetAndGetFieldName() {
		$input = new InputField('name');
		$this->assertEqual($input->getName(),'name');
	}
	function testSetAndGetValue() {
		$input = new InputField('name');
		$input->setValue('Dan');
		$this->assertEqual($input->getValue(),'Dan');
	}
	function testFiltersMethodReturnsArray() {
		$input = new InputField('name');
		$this->assertIsA($input->getFilters(),'array');
	}
	function testRulesMethodReturnsArray() {
		$input = new InputField('name');
		$this->assertIsA($input->getRules(),'array');
	}
	function testAddFilter() {
		$input = new InputField('name');
		$filter = new MockTrimFilter('field1');
		$filter2 = new MockTrimFilter('field2');
		$input->addFilter($filter);
		$this->assertEqual($input->getFilters(),array($filter));
		$input->addFilter($filter2);
		$this->assertEqual($input->getFilters(),array($filter,$filter2));
	}
	function testAddRuleWithDefaultProcessing() {
		$input = new InputField('name');
		$reqRule1 = new MockRequiredRule('field1 error');
		$input->addRule($reqRule1);
		
		$this->assertEqual($input->getRules(),array(array($reqRule1,InputField::PROCESS_STOP)));
		$reqRule2 = new MockRequiredRule('field1 error');
		$input->addRule($reqRule2);
		
		$this->assertEqual($input->getRules(),array(array($reqRule1,InputField::PROCESS_STOP),array($reqRule2,InputField::PROCESS_STOP)));
	}
	
	function testAddRuleWithSpecifiedProcessing() {
		$input = new InputField('name');
		$reqRule1 = new MockRequiredRule('field1 error');
		$input->addRule($reqRule1,InputField::PROCESS_CONTINUE);
		$this->assertEqual($input->getRules(),array(array($reqRule1,InputField::PROCESS_CONTINUE)));
		$reqRule2 = new MockRequiredRule('field2 error');
		$input->addRule($reqRule2,InputField::PROCESS_STOP);
		$this->assertEqual($input->getRules(),array(array($reqRule1,InputField::PROCESS_CONTINUE),array($reqRule2,InputField::PROCESS_STOP)));
	}

	function testProcessSingleRuleFailure() {
		$reqRule = new MockRequiredRule('field1','field1 error');
		$reqRule->expectOnce('process', array($this->ds));
		$input = new InputField('field1');
		$input->addRule($reqRule);
		$this->assertFalse($input->process($this->ds));
		$reqRule->tally();
	}
	function testProcessSingleRuleSuccess() {
		$this->ds->setReturnValue('get', 'data', array('field1'));
		$reqRule = new MockRequiredRule('field1','field1 error');
		$reqRule->setReturnValue('process', true);
		$reqRule->expectOnce('process', array($this->ds));
		$input = new InputField('field1');
		$input->addRule($reqRule);
		$this->assertTrue($input->process($this->ds));
		$reqRule->tally();
	}
	function testProcessMultipleRulesFailure()
	{
		$input = new InputField('field1');

		$reqRule = new MockRequiredRule('field1','field1 error');
		$reqRule->expectOnce('process', array($this->ds));
		$input->addRule($reqRule,InputField::PROCESS_CONTINUE);
		$lengthRule = new MockLengthRule('field1','field1 error', 1, 2);
		$lengthRule->expectOnce('process', array($this->ds));
		$input->addRule($lengthRule);
		$this->assertFalse($input->process($this->ds));
		$reqRule->tally();
		$lengthRule->tally();
	}
	function testProcessMultipleRulesSuccess() {
		$this->ds->setReturnValue('get', 'data', array('field1'));
		$input = new InputField('field1');
		
		$reqRule = new MockRequiredRule('field1','field1 error');
		$reqRule->setReturnValue('process', true);
		$reqRule->expectOnce('process', array($this->ds));
		$input->addRule($reqRule,InputField::PROCESS_CONTINUE);
		
		$lengthRule = new MockLengthRule('field1','field1 error', 1);
		$lengthRule->setReturnValue('process', true);
		$lengthRule->expectOnce('process', array($this->ds));
		$input->addRule($lengthRule);
		
		
		$this->assertTrue($input->process($this->ds));
		$reqRule->tally();
		$lengthRule->tally();
	}
	function testProcessFirstOfMultipleRulesWithStopParameter()
	{
		$input = new InputField('field1');

		$reqRule = new MockRequiredRule('field1','field1 error');
		$reqRule->expectOnce('process', array($this->ds));
		$input->addRule($reqRule,InputField::PROCESS_STOP);

		$lengthRule = new MockLengthRule('field1','field1 error', 1, 2);
		$lengthRule->expectNever('process');
		$input->addRule($lengthRule);
		$this->assertFalse($input->process($this->ds));
		$reqRule->tally();
		$lengthRule->tally();
	}
	function testProcessFilters()
	{
		$input = new InputField('field1');
		$filter = new MockTrimFilter('field1');
		$filter->expectOnce('process',array($this->ds), 'expecting process to be called');
		$filter2 = new MockTrimFilter('field1');
		$filter2->expectOnce('process',array($this->ds), 'expecting process to be called');
		$input->addFilter($filter);
		$input->addFilter($filter2);
		$input->process($this->ds);
		$filter->tally();
		$filter2->tally();
	}
	
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfInputField');
	$test->addTestCase(new TestOfInputField);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>