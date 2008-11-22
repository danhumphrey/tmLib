<?php
//tests includes
require_once('test-includes.php');


Mock::generate('HttpResponse');
Mock::generate('HttpRequest');

class TestOfServerPage extends WebTestCase {
	function __construct() {
		parent::WebTestCase();
	}
	
private $pagePath;
	private $validPageFile;
	private $validPageName;
	private $mapper;
	function setUp() 
	{
		$this->validPageName = 'testPage';
		$this->pagePath = SITE_PATH.DIRSEP.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'pages'.DIRSEP;
		$this->validPageFile = $this->pagePath .DIRSEP.$this->validPageName.'.php';
		
		if(!file_exists($this->pagePath)){
			mkdir($this->pagePath);
		}
		$this->createValidPage();
		$this->mapper = new PageMapper($this->pagePath);
	}
	function tearDown() 
	{
		if(file_exists($this->validPageFile)){
			unlink($this->validPageFile);
		}
		if(file_exists($this->pagePath)){
			rmdir($this->pagePath);
		}
	}
	function createValidPage()
	{
		$file = fopen($this->validPageFile,"w+");
		fwrite($file, $this->validPageName);
		fclose($file);
	}
	
	function testServerPage() 
	{
		$page = new ServerPage($this->validPageFile);
		$req = new MockHttpRequest();
		$res = new MockHttpResponse();
		$res->expectOnce('setContent',array($this->validPageName,));
		$page->execute($req,$res);
		$res->tally();
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfServerPage');
	$test->addTestCase(new TestOfServerPage);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>