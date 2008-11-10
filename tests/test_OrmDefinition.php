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

require_once('tst_orm_class_definitions.php');
class TestOfOrmDefinition extends UnitTestCase  {
	
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {}
	function tearDown() {}
	
	function testDefinitionWithoutRelations() {
		$def = new OrmDefinition('Article','ArticleTable',array('col1','col2'));
		$this->assertEqual($def->getClass(),'Article','getClass() should return Article');
		$this->assertEqual($def->getTable(),'ArticleTable','getTable() should return ArticleTable');
		$this->assertEqual($def->getColumns(),array('col1','col2'),'getColumns should return Array(\'col1\',\'col2\')');
		$this->assertEqual($def->getRelations(),array(),'getColumns() should return empty Array()');
	}
	function testDefinitionWithRelations() {
		$def = new OrmDefinition('Article','ArticleTable',array('col1','col2'),array('rel1','rel2'));
		$this->assertEqual($def->getRelations(),array('rel1','rel2'),'getRelations() should return Array(\'rel1\',\'rel2\')');
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfOrmDefinition');
	$test->addTestCase(new TestOfOrmDefinition());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>