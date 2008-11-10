<?php
if (! defined('SIMPLE_TEST')) {
	define('SIMPLE_TEST', 'C:\\Users\\Dan\\dev\\simpletest\\');
}

require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

require_once('../classes/TmLib.php');
	
//main tmLib includes file
require_once('../includes.php');

class TestOfAutoLoad extends UnitTestCase {
	private $path1;
	private $path2;
	private $path3;
	
	private $class1;
	private $class2;
	private $class3;
	private $class4;
	
	private $cacheDir;
	private $cacheFile;
	
	private $cachedClass1;
	private $cachedClassFile1;
	
	
	function __construct() {
		parent::UnitTestCase();
		$this->path1 = SITE_PATH.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'Classes'.DIRSEP;
		$this->path2 = SITE_PATH.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'Classes2'.DIRSEP;
		$this->path3 = SITE_PATH.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'Classes3'.DIRSEP;
		$this->cacheDir = SITE_PATH.'tmLib'.DIRSEP.'tests'.DIRSEP.'temp'.DIRSEP.'Cache'.DIRSEP;
		$this->cacheFile = $this->cacheDir.'autoload_cache.php';
		$this->cachedClass1 = 'CachedClass1';
		$this->cachedClass2 = 'CachedClass2';
		
	}
	function setUp() {
		
		//create dirs
		
		//class dirs
		if(!file_exists($this->path1)){
			mkdir($this->path1);
		}
		if(!file_exists($this->path2)){
			mkdir($this->path2);
		}
		if(!file_exists($this->path3)){
			mkdir($this->path3);
		}
		//cache
		if(!file_exists($this->cacheDir)){
			mkdir($this->cacheDir);
		}
		$this->createTestClasses();
	}
	
	function tearDown() {
		/*
		//kill cache file
		if(file_exists($this->cacheFile)){
			unlink($this->cacheFile);
		}
		//kill class files
		
		if(file_exists($this->class1)){
			unlink($this->class1);
		}
		if(file_exists($this->class2)){
			unlink($this->class2);
		}
		if(file_exists($this->class3)){
			unlink($this->class3);
		}
		if(file_exists($this->class4)){
			unlink($this->class4);
		}
		if(file_exists($this->cachedClassFile1)){
			unlink($this->cachedClassFile1);
		}
		if(file_exists($this->cachedClassFile2)){
			unlink($this->cachedClassFile2);
		}
		
		//kill dirs
		if(file_exists($this->path1)){
			rmdir($this->path1);
		}
		if(file_exists($this->path2)){
			rmdir($this->path2);
		}
		if(file_exists($this->path3)){
			rmdir($this->path3);
		}
		
		if(file_exists($this->cacheDir)){
			rmdir($this->cacheDir);
		}
		*/
	}

	function createTestClasses()
	{
		$this->class1 = $this->path1 .'AutoLoadTest1.php';
		
		$file = fopen($this->class1,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class AutoLoadTest1 {'. "\n");
		fwrite($file, 'function execute() {'. "\n");
		fwrite($file, 'return \'execute called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, 'function index(){'. "\n");
		fwrite($file, 'return \'index called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);
		
		$this->class2 = $this->path1 .'AutoLoadTest2.inc';
		
		$file = fopen($this->class2,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class AutoLoadTest2 {'. "\n");
		fwrite($file, 'function execute() {'. "\n");
		fwrite($file, 'return \'execute called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, 'function index(){'. "\n");
		fwrite($file, 'return \'index called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);
		
		$this->class3 = $this->path2 .'AutoLoadPath2.php';
		
		$file = fopen($this->class3,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class AutoLoadPath2 {'. "\n");
		fwrite($file, 'function execute() {'. "\n");
		fwrite($file, 'return \'execute called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, 'function index(){'. "\n");
		fwrite($file, 'return \'index called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);

		$this->class4 = $this->path3 .'AutoLoadPath3.php';
		
		$file = fopen($this->class4,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class AutoLoadPath3 {'. "\n");
		fwrite($file, 'function execute() {'. "\n");
		fwrite($file, 'return \'execute called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, 'function index(){'. "\n");
		fwrite($file, 'return \'index called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);
		
		$this->cachedClassFile1 = $this->path1 . $this->cachedClass1 . '.php';
		
		$file = fopen($this->cachedClassFile1,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class '. $this->cachedClass1 .'{'. "\n");
		fwrite($file, 'function execute() {'. "\n");
		fwrite($file, 'return \'execute called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, 'function index(){'. "\n");
		fwrite($file, 'return \'index called\';'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '}'. "\n");
		fwrite($file, '?>'. "\n");
		fclose($file);
		
		$this->cachedClassFile2 = $this->path1 . $this->cachedClass2 . '.php';
		
		$file = fopen($this->cachedClassFile2,"w+");
		fwrite($file, '<?PHP'. "\n");
		fwrite($file, 'class '. $this->cachedClass2 .'{'. "\n");
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
	
	function testInvalidClassReturnsFalse() {	
		$this->assertFalse(AutoLoad::loadClass('NonsenseClass'));
	}
	
	function testValidClassIsLoadedAndReturnsTrue() {
		$this->assertFalse(class_exists('AutoLoadTest1'));
		AutoLoad::addClassPath($this->path1);
		$this->assertTrue(AutoLoad::loadClass('AutoLoadTest1'));
		$this->assertTrue(class_exists('AutoLoadTest1'));		
	}
	function testAddFileNameTemplate() {
		AutoLoad::addClassPath($this->path1);
		$this->assertFalse(AutoLoad::loadClass('AutoLoadTest2'),'should fail as template doesnt match');
		AutoLoad::addFileNameTemplate('@CLASS@.inc');
		$this->assertTrue(AutoLoad::loadClass('AutoLoadTest2'),'should pass as naming template has been added');
	}
	function testMultipleClassPaths() {	
		$this->assertFalse(class_exists('AutoLoadPath2'));
		$this->assertFalse(class_exists('AutoLoadPath3'));
		AutoLoad::addClassPath($this->path2);
		AutoLoad::addClassPath($this->path3);
		
		$this->assertTrue(AutoLoad::loadClass('AutoLoadPath2'));
		$this->assertTrue(AutoLoad::loadClass('AutoLoadPath3'));
		
		$this->assertTrue(class_exists('AutoLoadPath2'));
		$this->assertTrue(class_exists('AutoLoadPath3'));
	}
	function testSetCacheFile() {
		$this->assertFalse(AutoLoad::setCacheFile('/path/to/rubbish/Dir/file.php'),'invalid cache file should return false');
		$this->assertTrue(AutoLoad::setCacheFile($this->cacheFile),'valid cache file should return true');
	}
	function testClassesAddedToCacheFile() {
		AutoLoad::addClassPath($this->path1);
		AutoLoad::setCacheFile($this->cacheFile);
		AutoLoad::loadClass($this->cachedClass1);
		AutoLoad::loadClass($this->cachedClass2);
		//inlcude the cache file and compare expected
		include($this->cacheFile);
		$this->assertEqual($tmLibAutoLoadCacheArray, array('CachedClass1'=>''.$this->cachedClassFile1,'CachedClass2'=>''.$this->cachedClassFile2));
		
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfAutoLoad');
	$test->addTestCase(new TestOfAutoLoad);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>