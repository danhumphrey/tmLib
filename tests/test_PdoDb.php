<?php

class TestOfPdoDb extends UnitTestCase  {
	
	/**
	 * db instance
	 * @var TmPdoDb
	 */
	private $db;
	
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {
		$this->db = new TmPdoDb(PDO_DSN,PDO_USER,PDO_PW,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));;
	}
	function tearDown() {
		unset($this->db);
	}
	function testIsPdo() {
		$this->assertTrue(is_a($this->db,'PDO'));
	}
	function testCreateSelectQuery() {
		$q = $this->db->createSelectQuery();
		$this->assertTrue(is_a($q,'TmSelectQuery'));
	}
	function testCreateInsertQuery() {
		$q = $this->db->createInsertQuery();
		$this->assertTrue(is_a($q,'TmInsertQuery'));
	}
	function testCreateUpdateQuery() {
		$db = new TmPdoDb(PDO_DSN,PDO_USER,PDO_PW,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$q = $this->db->createUpdateQuery();
		$this->assertTrue(is_a($q,'TmUpdateQuery'));
	}
	function testCreateDeleteQuery() {
		$db = new TmPdoDb(PDO_DSN,PDO_USER,PDO_PW,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$q = $this->db->createDeleteQuery();
		$this->assertTrue(is_a($q,'TmDeleteQuery'));
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfPdoDb');
	$test->addTestCase(new TestOfPdoDb());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>