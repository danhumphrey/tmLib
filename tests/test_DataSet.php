<?php
if (! defined('SIMPLE_TEST')) {
	define('SIMPLE_TEST', 'C:\\Users\\Dan\\dev\\simpletest\\');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

//main tmLib includes file
require_once('../includes.php');

//base UnitTestCase for testing $_GET data

//$_GET tests
class TestOfDataSet extends UnitTestCase  {
	private $ds;
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {
		$this->ds = new DataSet();
	}
	function tearDown() {
		unset($this->ds);
	}
	function testKeyExists() {
		//single
		$this->assertFalse($this->ds->keyExists('testkey'));
		$this->ds->set('testKey','testValue');
		$this->assertTrue($this->ds->keyExists('testKey'));
		//array
		$this->assertFalse($this->ds->keyExists('testArrayKey'));
		$this->ds->set('testArrayKey',array('testval','testVal2'));
		$this->assertTrue($this->ds->keyExists('testArrayKey'));
	}
	function testArrayKeyExists() {
		$this->assertFalse($this->ds->arrayKeyExists('testKey','testArrayKey'));
		$this->ds->set('testKey',array('testArrayKey'=>'testval'));
		$this->assertTrue($this->ds->arrayKeyExists('testKey','testArrayKey'));
	}
	function testValidKeyContainsCorrectValue() {
		$this->ds->set('testKey','testValue');
		$this->assertEqual($this->ds->get('testKey'), 'testValue');
	}
	function testInvalidKeyContainsNullValue() {
		$this->assertNull($this->ds->get('NullRubbish'));
	}
	function testGetDataReturnsArray() {
		$this->assertEqual($this->ds->getData(), array());
	}
	function testGetDataReturnsCorrectData() {
		$this->ds->set('testKey','testValue');
		$this->assertEqual($this->ds->getData(), array('testKey' => 'testValue'));
	}
	function testGetAndSetArray() {
		$data = array('name'=>'Dan', 'age'=>29);
		$this->ds->set('formVars',$data);
		$this->assertEqual($this->ds->get('formVars'),$data);
	}
	function testUpdate() {
		//standard var
		$this->ds->set('Test', 'Test');
		$this->assertEqual($this->ds->get('Test'),'Test');
		$this->ds->set('Test', 'TestX');
		$this->assertEqual($this->ds->get('Test'),'TestX');

		//array
		$data = array('name'=>'Dan', 'age'=>29);
		$this->ds->set('formVars',$data);
		$this->assertEqual($this->ds->get('formVars'),$data);
		$data2 = array('name'=>'Bob', 'age'=>18);
		$this->ds->set('formVars',$data2);
		$this->assertEqual($this->ds->get('formVars'),$data2);
	}
	function testUpdateArrayKey() {
		$data = array('name'=>'Dan', 'age'=>29);
		$this->ds->set('formVars',$data);
		$this->assertEqual($this->ds->get('formVars'),$data);
		$this->ds->setArrayKey('formVars','age', 30);
		$data['age'] = 30;
		$this->assertEqual($this->ds->get('formVars'),$data);
	}
	function testUpdateSingleToArray() {
		$data = array('name'=>'Dan', 'age'=>29);
		$this->ds->set('formVars','string');
		$this->assertEqual($this->ds->get('formVars'),'string');
		$this->ds->set('formVars',$data);
		$this->assertEqual($this->ds->get('formVars'),$data);
	}
	function testRemove() {
		$this->ds->set('testKey','testValue');
		$this->ds->set('testKey2','testValue2');
		$this->ds->set('testKey3','testValue3');
		$this->ds->remove('testKey2');
		$this->assertEqual($this->ds->getData(), array('testKey' => 'testValue', 'testKey3'=>'testValue3'));
	}
	function testRemoveArrayKey() {
		$this->ds->setArrayKey('test','arrayKey','testValue');
		$this->ds->setArrayKey('test','arrayKey2','testValue2');
		$this->assertEqual($this->ds->get('test'),array('arrayKey'=>'testValue','arrayKey2'=>'testValue2'),'key `test` should be testValue: %s');
		$this->ds->removeArrayKey('test','arrayKey');
		$this->assertEqual($this->ds->get('test'),array('arrayKey2'=>'testValue2'),'key `test`=>`arrayKey` should not exist: %s');
	}
	
	function testArrayAccess() {
		//set
		$this->ds['testKey'] = 'testValue';
		$this->assertEqual($this->ds->get('testKey'), 'testValue','testKey should have a value of `testValue`');
		//get
		$this->ds->set('testKey2','testValue');
		$this->assertEqual($this->ds['testKey2'], 'testValue','testKey2 should have a value of `testValue`');
		//deep array
		//$this->ds['testKey3']['testSubKey'] = 'testSubValue';
		//$this->assertEqual($this->ds->get('testKey3'),array('testSubKey'=>'testSubValue'),'array should match');
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfDataSet');
	$test->addTestCase(new TestOfDataSet());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>