<?php
if (! defined('SIMPLE_TEST')) {
	define('SIMPLE_TEST', 'C:\\web\\simpletest\\');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

class FCTestCommand implements IRequestHandler {
	
	function execute($request,$response)
	{
	}
}
//main tmLib includes file
require_once('../includes.php');
Mock::generate('HttpRequest');
Mock::generate('HttpResponse');
Mock::generate('FCTestCommand');
Mock::generate('ServerPage');
Mock::generate('PageMapper');
Mock::generate('CommandMapper');

class TestOfFrontController extends UnitTestCase {
	private $req;
	private $res;
	private $cmd;
	private $page;
	private $pageMapper;
	private $cmdMapper;
	
	
	function setUp()
	{
		$this->req = new MockHttpRequest();
		$this->res = new MockHttpResponse();
		$this->cmd = new MockFCTestCommand();
		$this->page = new MockServerPage();
		$this->pageMapper = new MockPageMapper();
		$this->cmdMapper = new MockCommandMapper();
	}
	function tearDown() {
		unset($this->req);
		unset($this->res);
		unset($this->cmd);
		unset($this->page);
		unset($this->pageMapper);
		unset($this->cmdMapper);
	}
	function __construct() {
		parent::UnitTestCase();
	}
	function testFCReturnsFalseWhenNoServerPageFound() {
		$fc = new FrontController($this->pageMapper);
		$this->pageMapper->setReturnValue('mapRequest','false');
		$this->assertFalse($fc->execute($this->req, $this->res));
	}
	function testFCExecutesPageWhenServerPageFound() {
		$fc = new FrontController($this->pageMapper);
		$this->page->expectOnce('execute',array($this->req,$this->res));
		$this->pageMapper->setReturnValue('mapRequest',$this->page);
		$this->assertTrue($fc->execute($this->req, $this->res));
		$this->page->tally();
	}
	function testFCReturnsFalseWhenNoCmdFound() {
		$fc = new FrontController($this->cmdMapper);
		$this->cmdMapper->setReturnValue('mapRequest','false');
		$this->assertFalse($fc->execute($this->req, $this->res));
	}
	function testFCExecutesPageWhenCmdFound() {
		$fc = new FrontController($this->cmdMapper);
		$this->cmd->expectOnce('execute',array($this->req,$this->res));
		$this->cmdMapper->setReturnValue('mapRequest',$this->cmd);
		$this->assertTrue($fc->execute($this->req, $this->res));
		$this->cmd->tally();
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfFrontController');
	$test->addTestCase(new TestOfFrontController);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>