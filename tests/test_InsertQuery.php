<?php
//tests includes
require_once('test-includes.php');

class TestOfInsertQuery extends UnitTestCase  {
	
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
	function testInsertInto() {
		$q = new TmInsertQuery($this->db);
		$q->insertInto('Test1');
		$s = $q->getSql();
		$this->assertEqual('INSERT INTO Test1 () VALUES ()',$s);
	}
	function testSet() { 
		$q = new TmInsertQuery($this->db);
		$q->insertInto('Test1')->set('col1','val1')->set('col2','val2');
		$s = $q->getSql();
		$this->assertEqual('INSERT INTO Test1 (col1, col2) VALUES (\'val1\', \'val2\')',$s);
	}

}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfInsertQuery');
	$test->addTestCase(new TestOfInsertQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>