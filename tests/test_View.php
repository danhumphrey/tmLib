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

class TestOfView extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function testPhpTalView() {
		//$view = new PhpTalView('test.html');
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfView');
	$test->addTestCase(new TestOfView);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>