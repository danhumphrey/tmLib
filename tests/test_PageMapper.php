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
        
class TestOfPageMapper extends UnitTestCase {
	private $pagePath;
	private $validPageFile;
	private $validPageName;
	private $mapper;
	private $req;
	
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
		Mock::generate('HttpRequest');
		$this->req = new MockHttpRequest();
	}
	
	function tearDown() 
	{
		if(file_exists($this->validPageFile)){
			unlink($this->validPageFile);
		}
		if(file_exists($this->pagePath)){
			rmdir($this->pagePath);
		}	
		$this->req = null;
	}
	function createValidPage()
	{
		$file = fopen($this->validPageFile,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, '<h1>'.$this->validPageName.'</h1>'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);
	}
	function __construct() {
		parent::UnitTestCase();
	}
	function testNoMatchReturnsNull() {
		$this->req->setReturnValue('get', 'RubbishPage', array('page'));
		$this->assertNull($this->mapper->mapRequest($this->req));
	}
	function testValidMatchReturnsCorrectCommand() {
		$this->req->setReturnValue('get', $this->validPageName, array('page'));
		$this->assertIsA($this->mapper->mapRequest($this->req),'ServerPage');
		
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfPageMapper');
	$test->addTestCase(new TestOfPageMapper);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>