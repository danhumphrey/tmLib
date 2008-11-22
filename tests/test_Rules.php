<?php
//tests includes
require_once('test-includes.php');

Mock::generate('DataSet');

function dummyCallbackFunction($val) {
	if($val == 'Dan')
	{
		return true;
	}
}
class dummyCallbackClass {
	static function dummyCallbackClassFunction($val) {
		if($val == 'Dan')
		{
			return true;
		}
	}
}
class TestOfRules extends UnitTestCase {
	private $ds;
	function setUp()
	{
		$this->ds = new MockDataSet();
	}
	function __construct() {
		parent::UnitTestCase();
	}
	function testRequiredRuleInvalid() {
		//null
		$rule = new RequiredRule('foo is required');
		$rule->setFieldName('foo');
		$this->ds->expectOnce('set',array('fooError','foo is required',));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	
	function testRequiredRuleValid() {
		$rule = new RequiredRule('bar is required');
		$rule->setFieldName('bar');
		$this->ds->setReturnValue('get', 'barValue', array('bar'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	
	function testOneRequiredRuleInvalid() {
		$rule = new OneRequiredRule('foo or bar are required',array('foo','bar'));
		$rule->setFieldName('foo');
		$this->ds->expectOnce('set',array('fooError','foo or bar are required',));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testOneRequiredValidOne() {
		$rule = new OneRequiredRule('foo or bar are required',array('foo','bar'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'fooValue', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testOneRequiredValidTwo() {
		$rule = new OneRequiredRule('foo or bar are required',array('foo','bar'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'barValue', array('bar'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	
	function testConditionalRequiredRuleReturnsTrueWhenConditionNotMet() {
		$rule = new ConditionalRequiredRule('foo and bar are required', 'bar');
		$rule->setFieldName('foo');
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testConditionalRequiredRuleReturnsFalseWhenConditionMetButFailed() {
		$this->ds->setReturnValue('get','barValue',array('bar'));
		$rule = new ConditionalRequiredRule('foo is required', 'bar');
		$rule->setFieldName('foo');
		$this->ds->expectOnce('set',array('fooError','foo is required',));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testConditionalRequiredRuleReturnsTrueWhenConditionMetAndPasses() {
		$this->ds->setReturnValue('get','barValue',array('bar'));
		$this->ds->setReturnValue('get','fooValue',array('foo'));
		$rule = new ConditionalRequiredRule('foo', 'foo is required', 'bar');
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testLengthRuleInvalidMinOnly() {
		//null
		$rule = new LengthRule('foo must be greater than 3 chars',3);
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'fo', array('foo'));
		$this->ds->expectOnce('set',array('fooError','foo must be greater than 3 chars',));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testLengthRuleValidMinOnly() {
		//null
		$rule = new LengthRule('foo must be greater than 3 chars',3);
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'val', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testLengthRuleInvalidMaxOnly() {
		//null
		$rule = new LengthRule('foo must be 2 chars or less',null,2);
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'val', array('foo'));
		$this->ds->expectOnce('set',array('fooError','foo must be 2 chars or less',));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testLengthRuleValidMaxOnly() {
		//null
		$rule = new LengthRule('foo must be 3 chars or less',null,3);
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'val', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testLengthRuleInvalidRange() {
		$msg = 'foo must be between 5 and 10 chars';
		$rule = new LengthRule($msg,5,10);
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'val', array('foo'));
		$this->ds->expectOnce('set',array('fooError',$msg,));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testLengthRuleValidRange() {
		$msg = 'foo must be between 5 and 10 chars';
		//null
		$rule = new LengthRule($msg,5,10);
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'validVal', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testComparisonRuleInvalid() {
		$msg = 'foo and fooCompare must be equal';
		//null
		$rule = new ComparisonRule($msg,'foo');
		$rule->setFieldName('fooCompare');
		$this->ds->setReturnValue('get', 'fooCompareValueIncorrect', array('fooCompare'));
		$this->ds->setReturnValue('get', 'fooValue', array('foo'));
		$this->ds->expectOnce('set',array('fooCompareError',$msg,));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	
	}
	function testComparisonRuleValid() {
		$msg = 'foo and fooCompare must be equal';
		//null
		$rule = new ComparisonRule($msg,'foo');
		$rule->setFieldName('fooCompare');
		$this->ds->setReturnValue('get', 'fooValue', array('fooCompare'));
		$this->ds->setReturnValue('get', 'fooValue', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();	
	}
	function testPatternRuleInvalid() {
		$msg = 'foo and fooCompare must be equal';
		//alpha, numeric and spaces
		$rule = new PatternRule($msg,'/^[a-zA-Z0-9 ]+$/');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', '8&AHHS*', array('foo'));
		$this->ds->expectOnce('set',array('fooError',$msg,));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testPatternRuleValid() {
		$msg = 'foo and fooCompare must be equal';
		//alpha, numeric and spaces
		$rule = new PatternRule($msg,'/^[a-zA-Z0-9 ]+$/');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', '8 AHHS', array('foo'));
		$this->ds->expectNever('set',array('fooError',$msg,));
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testAllowedValuesRuleInvalidSingle() {
		$msg = "foo must contain 'ABC'";
		$rule = new AllowedValuesRule($msg,array('ABC'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'DEF', array('foo'));
		$this->ds->expectOnce('set',array('fooError',$msg,));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testAllowedValuesRuleInvalidMultiple() {
		$msg = "foo must contain 'ABC' or 'GHI'";
		$rule = new AllowedValuesRule($msg,array('ABC','GHI'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'DEF', array('foo'));
		$this->ds->expectOnce('set',array('fooError',$msg,));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testAllowedValuesRuleValidSingle() {
		$msg = "foo must contain 'ABC'";
		$rule = new AllowedValuesRule($msg,array('ABC'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'ABC', array('foo'));
		$this->ds->expectNever('set',array('fooError',$msg,));
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testAllowedValuesRuleValidMultiple() {
		$msg = "foo must contain 'ABC', 'DEF' or 'GHI'";
		$rule = new AllowedValuesRule($msg,array('ABC','DEF','GHI'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'DEF', array('foo'));
		$this->ds->expectNever('set',array('fooError',$msg,));
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}

	function testEmailRuleInvalid() {
		$rule = new EmailRule('foo should be a valid email address');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'dh@abcc.', array('foo'));
		$this->ds->expectOnce('set',array('fooError','foo should be a valid email address'));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testEmailRuleValid() {
		$rule = new EmailRule('bar should be a valid email address');
		$rule->setFieldName('bar');
		$this->ds->setReturnValue('get', 'me@myemail.com.au', array('bar'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testNumericRuleInvalid() {
		$rule = new NumericRule('foo should be a number');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'abc', array('foo'));
		$this->ds->expectOnce('set',array('fooError','foo should be a number'));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testNumericRuleValidInt() {
		$rule = new NumericRule('foo should be a number');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', '155', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testNumericRuleValidFloat() {
		$rule = new NumericRule('foo should be a number');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', '155.65', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testCallbackRuleInvalid() {
		$rule = new CallbackRule('the function returned false','dummyCallbackFunction');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'Bob', array('foo'));
		$this->ds->expectOnce('set',array('fooError','the function returned false'));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testCallbackRuleValid() {
		$rule = new CallbackRule('the function returned false','dummyCallbackFunction');
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'Dan', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}
	function testClassCallbackRuleInvalid() {
		$rule = new CallbackRule('the function returned false',array('dummyCallbackClass','dummyCallbackClassFunction'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'Bob', array('foo'));
		$this->ds->expectOnce('set',array('fooError','the function returned false'));
		$this->assertFalse($rule->process($this->ds));
		$this->ds->tally();
	}
	function testClassCallbackRuleValid() {
		$rule = new CallbackRule('the function returned false',array('dummyCallbackClass','dummyCallbackClassFunction'));
		$rule->setFieldName('foo');
		$this->ds->setReturnValue('get', 'Dan', array('foo'));
		$this->ds->expectNever('set');
		$this->assertTrue($rule->process($this->ds));
		$this->ds->tally();
	}

}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfRules');
	$test->addTestCase(new TestOfRules);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>