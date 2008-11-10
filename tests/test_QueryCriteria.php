<?php

class TestOfQueryCriteria extends UnitTestCase  {

	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {
	}
	function tearDown() {
	}
	function testLandSingle() {
		$c = new TmQueryCriteria();
		$res = $c->land('val1');
		$this->assertEqual('( val1 )',$res);
	}
	function testLandMultiple() {
		$c = new TmQueryCriteria();
		$res = $c->land('val1', 'val2', 'val3');
		$this->assertEqual('( val1 AND val2 AND val3 )',$res);
	}
	function testLorSingle() {
		$c = new TmQueryCriteria();
		$res = $c->lor('val1');
		$this->assertEqual('( val1 )',$res);
	}
	function testLorMultiple() {
		$c = new TmQueryCriteria();
		$res = $c->lor('val1', 'val2', 'val3');
		$this->assertEqual('( val1 OR val2 OR val3 )',$res);
	}
	function testEquals() {
		$c = new TmQueryCriteria();
		$res = $c->eq('col1', 'val1');
		$this->assertEqual('col1 = val1',$res);
	}
	function testNotEqual() {
		$c = new TmQueryCriteria();
		$res = $c->ne('col1', 'val1');
		$this->assertEqual('col1 <> val1',$res);
	}
	function testLessThan() {
		$c = new TmQueryCriteria();
		$res = $c->lt('col1', 'val1');
		$this->assertEqual('col1 < val1',$res);
	}
	function testLessThanOrEqualTo() {
		$c = new TmQueryCriteria();
		$res = $c->lteq('col1', 'val1');
		$this->assertEqual('col1 <= val1',$res);
	}
	function testGreaterThan() {
		$c = new TmQueryCriteria();
		$res = $c->gt('col1', 'val1');
		$this->assertEqual('col1 > val1',$res);
	}
	function testGreaterThanOrEqualTo() {
		$c = new TmQueryCriteria();
		$res = $c->gteq('col1', 'val1');
		$this->assertEqual('col1 >= val1',$res);
	}
	function testlike() {
		$c = new TmQueryCriteria();
		$res = $c->like('col1', 'val1');
		$this->assertEqual('col1 LIKE val1',$res);
	}
	function testBetween() {
		$c = new TmQueryCriteria();
		$res = $c->between('col1', 'val1', 'val2');
		$this->assertEqual('col1 BETWEEN val1 AND val2',$res);
	}
	function testIn() {
		$c = new TmQueryCriteria();
		$res = $c->in('col1','val1, val2');
		$this->assertEqual('col1 IN (val1, val2)',$res);
	}
	function testInArray() {
		$c = new TmQueryCriteria();
		$res = $c->in('col1', array('val1','val2'));
		$this->assertEqual('col1 IN (val1, val2)',$res);
	}
	function testNot() {
		$c = new TmQueryCriteria();
		$res = $c->not('col1');
		$this->assertEqual('NOT col1',$res);
	}
	function testIsNull() {
		$c = new TmQueryCriteria();
		$res = $c->isNull('col1');
		$this->assertEqual('col1 IS NULL',$res);
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfQueryCriteria');
	$test->addTestCase(new TestOfQueryCriteria());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>