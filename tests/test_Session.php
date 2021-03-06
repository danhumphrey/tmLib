<?php
//tests includes
require_once('test-includes.php');


class TestOfSession extends UnitTestCase {
	/**
	 * @access private
	 * @var Session $session
	 */
	private $session;
	private $sessionId;
	
	function __construct() {
		parent::UnitTestCase();
		$this->sessionId = session_id();
	}
	function setUp() {
		unset($_SESSION);
		$this->session = new Session();
	}
	function tearDown() {
		unset($this->session); 	
	}
	function testKeyExists() {
		//single
		$this->assertFalse($this->session->keyExists('testKey'));
		$this->session->set('testKey','testval');
		$this->assertTrue($this->session->keyExists('testKey'));
		unset($_SESSION['testKey']);
		//array
		$this->assertFalse($this->session->keyExists('testArrayKey'));
		$this->session->set('testArrayKey',array('testval','testVal2'));
		$this->assertTrue($this->session->keyExists('testArrayKey'));
		unset($_SESSION['testArrayKey']);	
	}
	function testArrayKeyExists() {
		$this->assertFalse($this->session->arrayKeyExists('testKey','testArrayKey'));
		$this->session->set('testKey',array('testArrayKey'=>'testval'));
		$this->assertTrue($this->session->arrayKeyExists('testKey','testArrayKey'));	
	}
	function testSessionStartedOnConstruct() {
		$this->assertEqual($this->sessionId,'','session id should not be set');
		$this->assertTrue(strlen(session_id())> 0,'session id should be set');
	}
	function testSameSessionIdForMultipleSessions() {
		$session2 = new Session();
		$id2 = session_id();
		$this->assertEqual($id1,$this->sessionId,'session ids should be equal');
		unset($session2);
	}
	function testGetDataReturnsArray() {
		$this->assertIsA($this->session->getData(),'array');
	}
	function testGetAndSetVariable() {
		$this->session->set('key1','val1');
		$this->assertEqual($this->session->get('key1'),'val1');
	}
	function testGetExternallySetVar() {
		$_SESSION['key2'] = 'val2';
		$this->assertEqual($this->session->get('key2'),'val2');
	}
	function testGetAndSetArray() {
		$data = array('name'=>'Dan', 'age'=>29);
		$this->session->set('formVars',$data);
		$this->assertEqual($this->session->get('formVars'),$data);
	}
	
	function testUpdate() {
		//standard var
		$this->session->set('Test', 'Test');
		$this->assertEqual($this->session->get('Test'),'Test');
		$this->session->set('Test', 'TestX');
		$this->assertEqual($this->session->get('Test'),'TestX');
		
		//array
		$data = array('name'=>'Dan', 'age'=>29);
		$this->session->set('formVars',$data);
		$this->assertEqual($this->session->get('formVars'),$data);
		$data2 = array('name'=>'Bob', 'age'=>18);
		$this->session->set('formVars',$data2);
		$this->assertEqual($this->session->get('formVars'),$data2);
	}
	function testUpdateArrayKey() {
		$data = array('name'=>'Dan', 'age'=>29);
		$this->session->set('formVars',$data);
		$this->assertEqual($this->session->get('formVars'),$data);
		$this->session->setArrayKey('formVars','age', 30);
		$data['age'] = 30;
		$this->assertEqual($this->session->get('formVars'),$data);
	}
	function testUpdateSingleToArray() {
		$data = array('name'=>'Dan', 'age'=>29);
		$this->session->set('formVars','string');
		$this->assertEqual($this->session->get('formVars'),'string');
		$this->session->set('formVars',$data);
		$this->assertEqual($this->session->get('formVars'),$data);
	}
	function testRemoveKey() {
		$this->session->set('test','testValue');
		$this->assertEqual($this->session->get('test'),'testValue','session key `test` should be testValue: %s');
		$this->session->remove('test');
		$this->assertNull($this->session->get('test'),'session key should not exist');
	}
	
	function testRemoveArrayKey() {
		$this->session->setArrayKey('test','arrayKey','testValue');
		$this->session->setArrayKey('test','arrayKey2','testValue2');
		$this->assertEqual($this->session->get('test'),array('arrayKey'=>'testValue','arrayKey2'=>'testValue2'),'session key `test` should be testValue: %s');
		$this->session->removeArrayKey('test','arrayKey');
		$this->assertEqual($this->session->get('test'),array('arrayKey2'=>'testValue2'),'session key `test`=>`arrayKey` should not exist: %s');
	}
	
	function testGetAndRemove(){
       $key = 'getAndRemoveKey';
       $val = 'ABC';
       $_SESSION[$key] = $val;
       $test = $this->session->getAndRemove($key);
       $this->assertEqual($test, $val);
       $test2 = $this->session->get($key);
       $this->assertNull($test2, 'session key not removed with getAndRemove method');
    }
	
	function testGetArrayKeyAndRemove(){
       $key = 'getAndRemoveArrayKey';
       $arrayKey = 'getArrayKeyAndRemoveArrayKey';	   
	   $val = 'ABC';
	   $arrayKey = 'getArrayKeyAndRemoveArrayKey2';
	   $val2 = 'DEF';
	   $_SESSION[$key] = array($arrayKey=>$val,$arrayKey2=>$val2 );
	   
       $test = $this->session->getArrayKeyAndRemove($key, $arrayKey);
       $this->assertEqual($test, $val);
       $test2 = $this->session->getArrayKey($key, $arrayKey);
       $this->assertNull($test2, 'session array key not removed with getAndRemove method');
	   //ensure other keys not removed
	   $test3 = $this->session->getArrayKey($key, $arrayKey2);
	   $this->assertEqual($test3, $val2); 
    }
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfSession');
	$test->addTestCase(new TestOfSession);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>