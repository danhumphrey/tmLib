<?php
//tests includes
require_once('test-includes.php');

class TestOfAuthDefinition extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function testDefinitionDefaults() {
		$def = new AuthDefinition('admin', 'letmein', 'testhash');
		$this->assertEqual($def->getLoginVar(),'admin','login var should be `admin`' );
		$this->assertEqual($def->getPasswordVar(),'letmein','password var should be `letmein`' );
		$this->assertEqual($def->getHashKey(),'testhash','hashkey should be `testhash`' );
		$this->assertEqual($def->getRetryCount(),0,'retryCount should be `3`' );
		$this->assertEqual($def->useMd5(),true,'md5 should be `true`');
	}
	function testDefinitionFull() {
		$def = new AuthDefinition('admin', 'letmein', 'testhash',5, false);
		$this->assertEqual($def->getLoginVar(),'admin','login var should be `admin`' );
		$this->assertEqual($def->getPasswordVar(),'letmein','password var should be `letmein`' );
		$this->assertEqual($def->getHashKey(),'testhash','hashkey should be `testhash`' );
		$this->assertEqual($def->getRetryCount(),5,'retryCount should be `5`' );
		$this->assertEqual($def->useMd5(),false,'md5 should be `false`' );
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfAuthDefinition');
	$test->addTestCase(new TestOfAuthDefinition);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>