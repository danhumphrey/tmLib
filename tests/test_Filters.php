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
Mock::generate('DataSet');

class TestOfFilters extends UnitTestCase {
	private $ds;
	function setUp()
	{
		$this->ds = new MockDataSet();
	}
	function __construct() {
		parent::UnitTestCase();
	}
	function testTrimFilter() {
		//null
		$filter = new TrimFilter('foo');
		$this->ds->setReturnValue('get', ' foo with spaces ', array('foo'));
		$this->ds->expectOnce('set',array('foo','foo with spaces',));
		$filter->process($this->ds);
		$this->ds->tally();
	}
	function testTrimFilterSetFieldName() {
		//null
		$filter = new TrimFilter();
		$filter->setFieldName('foo');
		$this->ds->setReturnValue('get', ' foo with spaces ', array('foo'));
		$this->ds->expectOnce('set',array('foo','foo with spaces',));
		$filter->process($this->ds);
		$this->ds->tally();
	}
	function testConcatFilterDefaultConcatenator() {
		//null
		$filter = new ConcatFilter('newField',array('field1', 'field2'));
		$this->ds->setReturnValue('get', 'value1', array('field1'));
		$this->ds->setReturnValue('get', 'value2', array('field2'));
		$this->ds->expectOnce('set',array('newField','value1value2',));
		$filter->process($this->ds);
		$this->ds->tally();
	}
	function testConcatFilterCustomConcatenator() {
		//null
		$filter = new ConcatFilter('newField',array('field1', 'field2', 'field3'),'-');
		$this->ds->setReturnValue('get', 'value1', array('field1'));
		$this->ds->setReturnValue('get', 'value2', array('field2'));
		$this->ds->setReturnValue('get', 'value3', array('field3'));
		$this->ds->expectOnce('set',array('newField','value1-value2-value3',));
		$filter->process($this->ds);
		$this->ds->tally();
	}
	
	function testStripTagsFilter() {
		$filter = new StripTagsFilter('foo');
		$this->ds->setReturnValue('get','<b>some text</b><br/> <script language="JavaScript>doSomething()</script>');
		$this->ds->expectOnce('set',array('foo','some text doSomething()',));
		$filter->process($this->ds);
		$this->ds->tally();
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfFilters');
	$test->addTestCase(new TestOfFilters);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>