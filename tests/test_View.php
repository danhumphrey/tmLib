<?php
//tests includes
require_once('test-includes.php');


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