<?php

class TestQuery extends TmQuery {
	function __construct($db) {
		parent::__construct($db);
	}
	public function addColumns($columns) {
		parent::addColumns($columns);
	}
	public function addTables($tables) {
		parent::addTables($tables);
	}
	public function tables() {
		return $this->tables;
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
class TestOfQuery extends UnitTestCase  {
	
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
	function testGetColumnsReturnsStar() {
		$q = new TestQuery($this->db);
		$s = $q->columns();
		$this->assertEqual('*',$s);
	}
	function testSetAndGetColumns() {
		$q = new TestQuery($this->db);
		$q->setColumns(array('col1, col2'));
		$s = $q->columns();
		$this->assertEqual('col1, col2',$s);
	}
	function testAddColumns() {
		$q = new TestQuery($this->db);
		$q->addColumns('col1');
		$s = $q->columns();
		$this->assertEqual('col1',$s);
	}
	function testAddColumnsMultipleCalls() {
		$q = new TestQuery($this->db);
		$q->addColumns('col1');
		$q->addColumns('col2');
		$s = $q->columns();
		$this->assertEqual('col1, col2',$s);
	}
	function testAddColumnsArray() {
		$q = new TestQuery($this->db);
		$q->addColumns(array('col1','col2'));
		$s = $q->columns();
		$this->assertEqual('col1, col2',$s);
	}
	function testAddTables() {
		$q = new TestQuery($this->db);
		$q->addTables('Table1');
		$s = $q->tables();
		$this->assertEqual('Table1',$s);
	}
	function testAddTablesMultipleCalls() {
		$q = new TestQuery($this->db);
		$q->addTables('Table1');
		$q->addTables('Table2');
		$s = $q->tables();
		$this->assertEqual('Table1, Table2',$s);
	}
	function testAddTablesArray() {
		$q = new TestQuery($this->db);
		$q->addTables(array('Table1','Table2'));
		$s = $q->tables();
		$this->assertEqual('Table1, Table2',$s);
	}
	function testBindParamNamed() {
		$q = new TestQuery($this->db);
		$param = 'test';
		$s = $q->bindParam($param, ':param');
		$this->assertEqual(':param',$s);
	}
	function testBindParamDefaultName() {
		$q = new TestQuery($this->db);
		$param = 'test';
		$s = $q->bindParam($param);
		$this->assertEqual(':bind1',$s);
	}
	function testBindParamDefaultNamesMultipleCalls() {
		$q = new TestQuery($this->db);
		$param = 'test';
		$param2 = 'test2';
		$s = $q->bindParam($param);
		$this->assertEqual(':bind1',$s);
		$s = $q->bindParam($param2);
		$this->assertEqual(':bind2',$s);
	}
	function testBindValueNamed() {
		$q = new TestQuery($this->db);
		$param = 'test';
		$s = $q->bindValue($param, ':param');
		$this->assertEqual(':param',$s);
	}
	function testBindValueDefaultName() {
		$q = new TestQuery($this->db);
		$param = 'test';
		$s = $q->bindValue($param);
		$this->assertEqual(':bind1',$s);
	}
	function testBindValueDefaultNamesMultipleCalls() {
		$q = new TestQuery($this->db);
		$param = 'test';
		$param2 = 'test2';
		$s = $q->bindValue($param);
		$this->assertEqual(':bind1',$s);
		$s = $q->bindValue($param2);
		$this->assertEqual(':bind2',$s);
	}
	
	function testExpression() {
		$q = new TestQuery($this->db);
		$e = $q->expression();
		$this->assertTrue(is_a($e,'TmQueryExpression'));
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfQuery');
	$test->addTestCase(new TestOfQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>