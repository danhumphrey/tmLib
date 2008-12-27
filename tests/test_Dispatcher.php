<?php
//tests includes
require_once('test-includes.php');


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

class TestOfDispatcher extends UnitTestCase {
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
	function testDispatcherReturnsFalseWhenNoServerPageFound() {
		$fc = new Dispatcher($this->pageMapper);
		$this->pageMapper->setReturnValue('mapRequest','false');
		$this->assertFalse($fc->execute($this->req, $this->res));
	}
	function testDispatcherExecutesPageWhenServerPageFound() {
		$fc = new Dispatcher($this->pageMapper);
		$this->page->expectOnce('execute',array($this->req,$this->res));
		$this->pageMapper->setReturnValue('mapRequest',$this->page);
		$this->assertTrue($fc->execute($this->req, $this->res));
		$this->page->tally();
	}
	function testDispatcherReturnsFalseWhenNoCmdFound() {
		$fc = new Dispatcher($this->cmdMapper);
		$this->cmdMapper->setReturnValue('mapRequest','false');
		$this->assertFalse($fc->execute($this->req, $this->res));
	}
	function testDispatcherExecutesPageWhenCmdFound() {
		$fc = new Dispatcher($this->cmdMapper);
		$this->cmd->expectOnce('execute',array($this->req,$this->res));
		$this->cmdMapper->setReturnValue('mapRequest',$this->cmd);
		$this->assertTrue($fc->execute($this->req, $this->res));
		$this->cmd->tally();
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfDispatcher');
	$test->addTestCase(new TestOfDispatcher);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>