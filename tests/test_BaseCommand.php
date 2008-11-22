<?php
//tests includes
require_once('test-includes.php');


Mock::generate('HttpRequest');
Mock::generate('HttpResponse');

class TestBaseCommand extends BaseCommand {
	public $canExecuteCalled = false;

	function getDsValue($key) {
		return $this->dataSet->get($key);
	}
	protected function canExecute() {
		$this->canExecuteCalled = true;
		return true;
	}
}
class TestOfBaseCommand extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}

	function testExecute() {
		$cmd = new TestBaseCommand();
		$req = new MockHttpRequest();
		$res = new MockHttpResponse();
		//setup data for request
		$req->setReturnValue('getData', array('testKey'=>'testVal'));
		$cmd->execute($req, $res);
		//test that canExecute was called
		$this->assertTrue($cmd->canExecuteCalled,'canExecute method was not called');
		//test that data was populated in dataSet
		$this->assertEqual($cmd->getDsValue('testKey'),'testVal');
			
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfBaseCommand');
	$test->addTestCase(new TestOfBaseCommand);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>