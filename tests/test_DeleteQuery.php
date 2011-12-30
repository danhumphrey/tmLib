<?php
//tests includes
require_once('test-includes.php');

class TestOfDeleteQuery extends UnitTestCase  {
	
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
	function testDeleteFrom() {
		$q = new TmDeleteQuery($this->db);
		$q->deleteFrom('Test1');
		$s = $q->getSql();
		$this->assertEqual('DELETE FROM Test1',$s);
	}
	function testNestedCalls() {
		$q = new TmDeleteQuery($this->db);
		$c = $q->criteria();
		$q->deleteFrom('Test1')->where($c->eq('col1','val0'))->limit(10,2);
		$s = $q->getSql();
		$this->assertEqual('DELETE FROM Test1 WHERE col1 = val0 LIMIT 10, 2',$s);
	}

}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfDeleteQuery');
	$test->addTestCase(new TestOfDeleteQuery());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>