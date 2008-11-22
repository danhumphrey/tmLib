<?php
//tests includes
require_once('test-includes.php');


class TestOfRegistry extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function testSetAndGet() {
		Registry::set('testKey','testValue');
		$this->assertEqual(Registry::get('testKey'),'testValue');
	}
	function testInvalidKeyExistsReturnsFalse() {
		$this->assertFalse(Registry::keyExists('testkeyThatDoesntExist'));
	}
	function testValidGetKeyExistsReturnsTrue() {
		Registry::set('testKey','testValue');
		$this->assertTrue(Registry::keyExists('testKey'));
	}
	
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfRegistry');
	$test->addTestCase(new TestOfRegistry);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>