<?php

Mock::generate('TmSelectQuery');

class TestOfSubSelectQuery extends UnitTestCase  {
	
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
	function testBindParamCallsParent() {
		$q = new MockTmSelectQuery($this->db);
		$name = 'Dan';
		$q->expectOnce('bindParam',array($name,null));
		$sub = new TmSubSelectQuery($q);
		$sub->bindParam($name);
		$q->tally();
	}
	function testBindValueCallsParent() {
		$q = new MockTmSelectQuery($this->db);
		$name = 'Dan';
		$q->expectOnce('bindValue',array($name,null));
		$sub = new TmSubSelectQuery($q);
		$sub->bindValue($name);
		$q->tally();
	}
	function testGetSql() {
		$q = new MockTmSelectQuery($this->db);
		$name = 'Dan';
		$sub = new TmSubSelectQuery($q);
		$sub->select('*')->from('Table1');
		$sub->bindValue($name);
		$this->assertEqual($sub->getSql(), 'SELECT * FROM Table1');
	}
	function testSubSelectReturn() {
		$q = new TmSelectQuery($this->db);
		$qsub = new TmSubSelectQuery($q);
		$qsub2 = $qsub->subSelect();
		$this->assertTrue(is_a($qsub,'TmSubSelectQuery'));
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfSubSelectQuery');
	$test->addTestCase(new TestOfSubSelectQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>