<?php
//tests includes
require_once('test-includes.php');

class TestOfSelectQuery extends UnitTestCase  {
	
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
	function testFrom() {
		$q = new TmSelectQuery($this->db);
		$q->from('Test1');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1',$s);
	}
	function testFromArray() {
		$q = new TmSelectQuery($this->db);
		$q->select('*');
		$q->from(array('Test1','Test2'));
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1, Test2',$s);
	}
	function testMultipleFromCalls() {
		$q = new TmSelectQuery($this->db);
		$q->select('*');
		$q->from('Test1');
		$q->from('Test2');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1, Test2',$s);
	}
	function testSelectColumns() {
		$q = new TmSelectQuery($this->db);
		$q->select('col1');
		$q->from('Test1');
		$s = $q->getSql();
		$this->assertEqual('SELECT col1 FROM Test1',$s);
		$q = new TmSelectQuery($this->db);
		$q->select('col1, col2');
		$q->from('Test1');
		$s = $q->getSql();
		$this->assertEqual('SELECT col1, col2 FROM Test1',$s);
	}
	function testSelectColumnsMultipleCalls() {
		$q = new TmSelectQuery($this->db);
		$q->select('col1');
		$q->from('Test1');
		$s = $q->getSql();
		//1 column
		$this->assertEqual('SELECT col1 FROM Test1',$s);
		$q->select('col2');
		$s = $q->getSql();
		//2 columns
		$this->assertEqual('SELECT col1, col2 FROM Test1',$s);
	}
	function testSelectColumnsArray() {
		$q = new TmSelectQuery($this->db);
		$q->select(array('col1','col2'));
		$q->from('Test1');
		$s = $q->getSql();
		$this->assertEqual('SELECT col1, col2 FROM Test1',$s);
	}
	function testGroupByString() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1, col2');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1, col2',$s);
	}
	function testGroupByArray() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy(array('col1', 'col2'));
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1, col2',$s);
	}
	function testMultipleGroupByCalls() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1');
		$q->groupBy('col2');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1, col2',$s);
	}
	function testMultipleGroupByCallsArray() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy(array('col1','col2'));
		$q->groupBy(array('col3','col4'));
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1, col2, col3, col4',$s);
	}
	function testHavingClauseString() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1', 'x > y');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1 HAVING x > y',$s);
	}
	function testMultipleHavingCalls() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1', 'x > y');
		$q->groupBy('col2', 'z <> w');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1 HAVING x > y, col2 HAVING z <> w',$s);
	}
	function testOrderByClause() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1');
		$q->orderBy('col1');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1 ORDER BY col1 ASC',$s);
	}
	function testOrderByType() {
		//ASC
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1');
		$q->orderBy('col1',TmSelectQuery::ASC);
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1 ORDER BY col1 ASC',$s);
		//DESC
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1');
		$q->orderBy('col1',TmSelectQuery::DESC);
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1 ORDER BY col1 DESC',$s);
	}
	function testMultipleOrderByCalls() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->from('Test1');
		$q->where($c->eq('col1','dan'));
		$q->groupBy('col1');
		$q->orderBy('col1');
		$q->orderBy('col2','DESC');
		$s = $q->getSql();
		$this->assertEqual('SELECT * FROM Test1 WHERE col1 = dan GROUP BY col1 ORDER BY col1 ASC, col2 DESC',$s);
	}
	function testNestedCalls() {
		$q = new TmSelectQuery($this->db);
		$q->select('col1, col2')->from('Table1')->from('Table2')->where($q->criteria()->eq('col1','dan'))->groupBy('col1')->orderBy('col1')->limit(10,2);
		$s = $q->getSql();
		$this->assertEqual('SELECT col1, col2 FROM Table1, Table2 WHERE col1 = dan GROUP BY col1 ORDER BY col1 ASC LIMIT 10, 2',$s);
	}

	function testSubSelectReturn() {
		$q = new TmSelectQuery($this->db);
		$qsub = $q->subSelect();
		$this->assertTrue(is_a($qsub,'TmSubSelectQuery'));
		
	}
	
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfSelectQuery');
	$test->addTestCase(new TestOfSelectQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>