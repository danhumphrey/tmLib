<?php

//tests includes
require_once('test-includes.php');

class TestOfUpdateQuery extends UnitTestCase  {
	
	/**
	 * db instance
	 * @var PDO
	 */
	private $db;
	
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {
		$this->db = new PDO(PDO_DSN,PDO_USER,PDO_PW,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));;
	}
	function tearDown() {
		unset($this->db);
	}
	function testUpdate() {
		$q = new TmUpdateQuery($this->db);
		$q->update('Test1');
		$s = $q->getSql();
		$this->assertEqual('UPDATE Test1',$s);
	}
	function testSet() { 
		$q = new TmUpdateQuery($this->db);
		$q->update('Test1');
		$q->set('col1','val1');
		$s = $q->getSql();
		$this->assertEqual('UPDATE Test1 SET col1 = \'val1\'',$s);
	}
	function testSetMultipleCalls() {
		$q = new TmUpdateQuery($this->db);
		$q->update('Test1');
		$q->set('col1','val1');
		$q->set('col2','val2');
		$s = $q->getSql();
		$this->assertEqual('UPDATE Test1 SET col1 = \'val1\', col2 = \'val2\'',$s);
	}
	function testNestedCalls() {
		$q = new TmUpdateQuery($this->db);
		$c = $q->criteria();
		$q->update('Test1')->set('col1','val1')->where($c->eq('col1','val0'))->limit(10,2);
		$s = $q->getSql();
		$this->assertEqual('UPDATE Test1 SET col1 = \'val1\' WHERE col1 = val0 LIMIT 10, 2',$s);
	}

}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfUpdateQuery');
	$test->addTestCase(new TestOfUpdateQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>