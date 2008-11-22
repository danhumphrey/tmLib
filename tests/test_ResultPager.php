<?php
//tests includes
require_once('test-includes.php');


class TestOfResultPager extends UnitTestCase {
	private $pager;
	function __construct() {
		parent::UnitTestCase();
	}
	function testPager() {
		$this->pager = new ResultPager(10,100,3);
		$this->assertEqual($this->pager->getTotalPages(),10,'expecting 10 pages %s');
		$this->assertEqual($this->pager->getStartRow(),20,'expecting start row to be 20 %s');
		//new
		$this->pager = new ResultPager(100,2009,5);
		$this->assertEqual($this->pager->getTotalPages(),21,'expecting 21 pages %s');
		$this->assertEqual($this->pager->getStartRow(),400,'expecting start row to be 400 %s');
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfResultPager');
	$test->addTestCase(new TestOfResultPager);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>