<?php

//tests includes
require_once('test-includes.php');

class TestWhereQuery extends TmWhereQuery {
	function __construct($db) {
		parent::__construct($db);
	}
	
	function generateSql() {
		if(isset($this->where)) {
			$this->sql = ' WHERE ' . $this->where;
		}
		//add limit criteria
		if(isset($this->limit)) {
			$this->sql .= $this->limit;
		}
	}
}
class TestOfWhereQuery extends UnitTestCase  {
	
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
	function testReturnOfCriteriaClass() {
		$q = new TestWhereQuery($this->db);
		$c = $q->criteria();
		$this->assertIsA($c,'TmQueryCriteria');
	}
	function testWhereCriteria() {
		$q = new TestWhereQuery($this->db);
		$c = $q->criteria();
		$q->where($c->eq('col1','dan'));
		$s = $q->getSql();
		$this->assertEqual(' WHERE col1 = dan',$s);
	}
	function testMultipleWhereCriteria() {
		$q = new TestWhereQuery($this->db);
		$c = $q->criteria();
		$q->where(
			$c->eq('col1','dan'),
			$c->ne('col2','25')
		);
		$s = $q->getSql();
		$this->assertEqual(' WHERE col1 = dan AND col2 <> 25',$s);
	}
	function testMultipleWhereCalls() {
		$q = new TestWhereQuery($this->db);
		$c = $q->criteria();
		$q->where($c->eq('col1','dan'));
		$q->where($c->eq('col2','32'));
		$s = $q->getSql();
		$this->assertEqual(' WHERE col1 = dan AND col2 = 32',$s);
	}
	function testLimitClause() {
		$q = new TestWhereQuery($this->db);
		$c = $q->criteria();
		$q->where($c->eq('col1','dan'));
		$q->limit(10);
		$s = $q->getSql();
		$this->assertEqual(' WHERE col1 = dan LIMIT 10',$s);
	}
	function testLimitClauseWithOffset() {
		$q = new TestWhereQuery($this->db);
		$c = $q->criteria();
		$q->where($c->eq('col1','dan'));
		$q->limit(10,2);
		$s = $q->getSql();
		$this->assertEqual(' WHERE col1 = dan LIMIT 10 OFFSET 2',$s);
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfWhereQuery');
	$test->addTestCase(new TestOfWhereQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>