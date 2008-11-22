<?php
//tests includes
require_once('test-includes.php');


class TestOfPathVars extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function testInvalidBaseUrlThrowsException() {
		try{
			$pathVars = new HttpPathVars('/invalid_rubbish/','all_tests.php');
			$this->fail('No Exception Thrown');
		}
		catch (Exception $e)
		{
			$this->pass('Exception thrown as expected');
		}
	}
	function testValidBaseUrlAndScript()
	{
		try {
			$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
			$this->pass();
		}
		catch (Exception $e)
		{
			$this->fail('Exception thrown unexpectedly');
		}
	}
	function testSizeOfPathVars()
	{
		$_SERVER['REQUEST_URI'] = '/tmLib/tests/all_tests.php/One';
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$this->assertEqual($pathVars->size(), 1);
		$_SERVER['REQUEST_URI'] = '/tmLib/tests/all_tests.php/One/Two';
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$this->assertEqual($pathVars->size(), 2);
	}
	function testFetchAllIsArray()
	{
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$this->assertTrue(is_array($pathVars->fetchAll()));
	}
	function testFetchAllValues()
	{
		$_SERVER['REQUEST_URI'] = '/tmLib/tests/all_tests.php/One/Two';
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$all = '';
		$allVars = $pathVars->fetchAll();
		foreach ($allVars as $var)
		{
			$all .= $var;
		}
		$this->assertEqual($all, 'OneTwo');
	}
	function testFetchByIndex()
	{
		$_SERVER['REQUEST_URI'] = '/tmLib/tests/all_tests.php/One/Two';
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$this->assertEqual($pathVars->fetchByIndex(0),'One');
		$this->assertEqual($pathVars->fetchByIndex(1),'Two');
	}
	function testFetch()
	{
		$_SERVER['REQUEST_URI'] = '/tmLib/tests/all_tests.php/One/Two';
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$all = '';
		while($var = $pathVars->fetch())
		{
			$all .= $var;
		}
		$this->assertEqual($all, 'OneTwo');
	}
	function testGetParamsIgnored()
	{
		$_SERVER['REQUEST_URI'] = '/tmLib/tests/all_tests.php?testKey=abc/One/Two';
		$pathVars = new HttpPathVars('/tmLib/tests/', 'all_tests.php');
		$this->assertEqual($pathVars->fetchByIndex(0),'One');
		$this->assertEqual($pathVars->fetchByIndex(1),'Two');
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfPathVars');
	$test->addTestCase(new TestOfPathVars);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>