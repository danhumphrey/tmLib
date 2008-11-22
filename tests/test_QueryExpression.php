<?php
//tests includes
require_once('test-includes.php');

class TestOfQueryExpression extends UnitTestCase  {
	
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {	
	}
	function tearDown() {
	}
	function testAdd() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->add(2,4), '(2+4)');
	}
	function testSubtract() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->subtract(4,2), '(4-2)');
	}
	function testMultiply() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->multiply(2,4), '(2*4)');
	}
	function testDivide() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->divide(4,2), '(4/2)');
	}
	function testConcatString() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->concat('2, 4'), 'CONCAT(2, 4)');
	}
	function testConcatArray() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->concat(array('2','4')), 'CONCAT(2, 4)');
	}
	function testSubstr() {
		$e = new TmQueryExpression();
		//without length
		$this->assertEqual($e->substr('field1',4), 'substring(field1 from 4)');
		//with length
		$this->assertEqual($e->substr('field1',4,2), 'substring(field1 from 4 for 2)');
	}
	function testCount() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->count('field1'), 'COUNT(field1)');
	}
	function testMin() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->min('field1'), 'MIN(field1)');
	}
	function testMax() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->max('field1'), 'MAX(field1)');
	}
	function testAvg() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->avg('field1'), 'AVG(field1)');
	}
	function testSum() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->sum('field1'), 'SUM(field1)');
	}
	function testRound() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->round('field1',2), 'ROUND(field1, 2)');
	}
	function testMod() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->mod('field1','field2'), 'MOD(field1, field2)');
	}
	function testLength() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->length('field1'), 'LENGTH(field1)');
	}
	function testMd5() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->md5('field1'), 'MD5(field1)');
	}
	function testNow() {
		$e = new TmQueryExpression();
		$this->assertEqual($e->now(), 'NOW()');
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfQueryExpression');
	$test->addTestCase(new TestOfQueryExpression());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>