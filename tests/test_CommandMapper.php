<?php
//tests includes
require_once('test-includes.php');


Mock::generate('HttpRequest');
        
class TestOfCommandMapper extends UnitTestCase {
	private $commandPath;
	private $validCommandFile;
	private $validCommandClass;
	private $mapper;
	
	function setUp() 
	{
		$this->validCommandClass = 'TestCommand';
		$this->commandPath = SITE_PATH.DIRSEP.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'Commands'.DIRSEP;
		$this->validCommandFile = $this->commandPath .DIRSEP.$this->validCommandClass.'.php';
		
		if(!file_exists($this->commandPath)){
			mkdir($this->commandPath);
		}
		
		$this->createValidCommandClass();
		$this->mapper = new CommandMapper($this->commandPath);
	}
	
	function tearDown() 
	{
	
		if(file_exists($this->validCommandFile)){
			unlink($this->validCommandFile);
		}
		if(file_exists($this->commandPath)){
			rmdir($this->commandPath);
		}
	}
	function createValidCommandClass()
	{
		$file = fopen($this->validCommandFile,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class ' .$this->validCommandClass.' {'. "\n");
		fwrite($file, 'function execute() {'. "\n");
		fwrite($file, 'return \'execute called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, 'function index(){'. "\n");
		fwrite($file, 'return \'index called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);
	}
	function __construct() {
		parent::UnitTestCase();
	}
	function testNoMatchReturnsNull() {
		$req = new MockHttpRequest();
		$req->setReturnValue('get', 'RubbishTestCommand', array('cmd'));
		$this->assertNull($this->mapper->mapRequest($req));
	}
	function testValidMatchReturnsCorrectCommand() {
		$req = new MockHttpRequest();
		$req->setReturnValue('get', 'Test', array('cmd'));
		$this->assertIsA($this->mapper->mapRequest($req),$this->validCommandClass);
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfCommandMapper');
	$test->addTestCase(new TestOfCommandMapper);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>