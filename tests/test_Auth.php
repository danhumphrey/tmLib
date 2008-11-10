<?php
if (! defined('SIMPLE_TEST')) {
	define('SIMPLE_TEST', 'C:\\web\\simpletest\\');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

//main tmLib includes file
require_once('../includes.php');

class TestAuthValidator implements IAuthValidator {
	function validateAuth($request, $definition) {
	}
}

Mock::generate('Session');
Mock::generate('HttpRequest');
Mock::generate('AuthDefinition');
Mock::generate('TestAuthValidator');


class TestOfAuth extends UnitTestCase {
	private $request;
	private $session;
	private $def;
	private $val;
	
	private $login = 'admin';
	private $password = 'letmein';
	private $md5Password;
	private $hashKey = 'secretHash';
	private $retryCount = 3;

	function __construct() {
		$this->md5Password = md5($this->password);
		define('SESSION_HASH_NAME', 'tmLibAuthSessionHash');
		define('SESSION_RETRY_COUNT', 'tmLibAuthRetryCount');
		parent::UnitTestCase();
	}
	function setUp() {
		$this->request = new MockHttpRequest();
		$this->session = new MockSession();
		$this->def = new MockAuthDefinition('login','password',$this->hashKey);
		$this->def->setReturnValue('getLoginVar', 'login');
		$this->def->setReturnValue('getPasswordVar', 'password');
		$this->def->setReturnValue('getHashKey', $this->hashKey);
		$this->def->setReturnValue('getRetryCount', $this->retryCount);
		$this->val = new MockTestAuthValidator();
	}
	function tearDown() {
		unset($this->session);
		unset($this->def);
		unset($this->val);
		unset($_SESSION);
	}


	function testNoSessionInvalidLogin() {
		//use md5
		$this->def->setReturnValue('useMd5', true);
		//invalid response
		$this->val->setReturnValue('validateAuth', false);
		$this->val->expectOnce('validateAuth',array($this->request, $this->def),'expecting the validator validateAuth method to be called');
		//session calls
		$this->session->expectAt(0,'get',array(SESSION_HASH_NAME));
		$this->session->expectCallCount('get',1);
		$auth = new Auth($this->session,$this->def);
		$this->assertFalse($auth->confirmAuth($this->request,$this->val));
		$this->val->tally();
		$this->session->tally();
	}

	function testNoSessionValidLogin() {
		//use md5
		$this->def->setReturnValue('useMd5', true);
		//no session
		$this->session->setReturnValue('get','',array('tmLibAuthSessionHash'));
		//login vars
		$this->request->setReturnValue('get',$this->login,array('login'));
		$this->request->setReturnValue('get',$this->password,array('password'));
		//valid response
		$this->val->setReturnValue('validateAuth', true);
		$this->val->expectOnce('validateAuth',array($this->request, $this->def),'expecting the validator validateAuth method to be called');
		//expect session to be set
		$this->session->expectAt(0,'set',array('login',$this->login));
		$this->session->expectAt(1,'set',array('password',$this->md5Password));
		$this->session->expectAt(2,'set',array('tmLibAuthSessionHash',md5($this->hashKey.$this->login.$this->md5Password)));
		$this->session->expectCallCount('set',3);
		//execute
		$auth = new Auth($this->session,$this->def);
		$this->assertTrue($auth->confirmAuth($this->request,$this->val),'expecting confirmAuth to return true');
		$this->val->tally();
		$this->session->tally();
	}
	function testNoSessionValidLoginWithoutMd5() {
		//no md5
		$this->def->setReturnValue('useMd5', false);
		//no session
		$this->session->setReturnValue('get','',array('tmLibAuthSessionHash'));
		//login vars
		$this->request->setReturnValue('get',$this->login,array('login'));
		$this->request->setReturnValue('get',$this->password,array('password'));
		//valid response
		$this->val->setReturnValue('validateAuth', true);
		$this->val->expectOnce('validateAuth',array($this->request, $this->def),'expecting the validator validateAuth method to be called');
		//expect session to be set
		$this->session->expectAt(0,'set',array('login',$this->login));
		$this->session->expectAt(1,'set',array('password',$this->password));
		$this->session->expectAt(2,'set',array('tmLibAuthSessionHash',md5($this->hashKey.$this->login.$this->password)));
		$this->session->expectCallCount('set',3);
		//execute
		$auth = new Auth($this->session,$this->def,false);
		$this->assertTrue($auth->confirmAuth($this->request,$this->val),'expecting confirmAuth to return true');
		$this->val->tally();
		$this->session->tally();
	}
	function testSessionInvalidLogin() {
		//session values
		$this->session->setReturnValue('get','rubbishhash',array('tmLibAuthSessionHash'));
		$this->session->setReturnValue('get',$this->login,array('login'));
		$this->session->setReturnValue('get',$this->password,array('password'));
		//don't expect validator to be called
		$this->val->expectNever('validateAuth','not expecting the validator validateAuth method to be called');
		//expect calls to get session
		$this->session->expectAt(0,'get',array('tmLibAuthSessionHash'));
		$this->session->expectAt(1,'get',array('login'));
		$this->session->expectAt(2,'get',array('password'));
		$this->session->expectAt(3,'get',array('tmLibAuthSessionHash'));
		$this->session->expectCallCount('get',4);
		//execute
		$auth = new Auth($this->session,$this->def);
		$this->assertFalse($auth->confirmAuth($this->request,$this->val));
		$this->val->tally();
		$this->session->tally();
	}

	function testSessionValidLogin() {
		//session values
		$this->session->setReturnValue('get',md5($this->hashKey.$this->login.$this->md5Password),array('tmLibAuthSessionHash'));
		$this->session->setReturnValue('get',$this->login,array('login'));
		$this->session->setReturnValue('get',$this->md5Password,array('password'));
		//no validation
		$this->val->expectNever('validateAuth','not expecting the validator validateAuth method to be called');
		//expect calls to get session,
		$this->session->expectCallCount('get',4);
		$this->session->expectAt(0,'get',array('tmLibAuthSessionHash'));
		$this->session->expectAt(1,'get',array('login'));
		$this->session->expectAt(2,'get',array('password'));
		$this->session->expectAt(3,'get',array('tmLibAuthSessionHash'));
		$this->session->expectCallCount('get',4);
		//expect nothing to be set in session
		$this->session->expectNever('set');
		//execute
		$auth = new Auth($this->session,$this->def);
		$this->assertTrue($auth->confirmAuth($this->request,$this->val),'expcting confirmAuth to return true');
		$this->val->tally();
		$this->session->tally();
	}

	function testSessionValidLoginNoMd5() {
		//no md5
		$this->def->setReturnValue('useMd5', false);
		//session values
		$this->session->setReturnValue('get',md5($this->hashKey.$this->login.$this->password),array('tmLibAuthSessionHash'));
		$this->session->setReturnValue('get',$this->login,array('login'));
		$this->session->setReturnValue('get',$this->password,array('password'));
		//expect calls to get session,
		$this->session->expectAt(0,'get',array('tmLibAuthSessionHash'));
		$this->session->expectAt(1,'get',array('login'));
		$this->session->expectAt(2,'get',array('password'));
		$this->session->expectAt(3,'get',array('tmLibAuthSessionHash'));
		$this->session->expectCallCount('get',4);
		//don't expect validator to be called
		$this->val->expectNever('validateAuth','not expecting the validator validateAuth method to be called');
		//expect nothing to be set in session
		$this->session->expectNever('set');
		//execute
		$auth = new Auth($this->session,$this->def,false);
		$this->assertTrue($auth->confirmAuth($this->request,$this->val),'expcting confirmAuth to return true');
		$this->val->tally();
		$this->session->tally();
	}
	
	function testRetryCountThrowsException() {
		//invalid response
		$this->val->setReturnValue('validateAuth', false);
		$auth = new Auth($this->session,$this->def);
		//login vars
		$this->request->setReturnValue('get',$this->login,array('login'));
		$this->request->setReturnValue('get',$this->password,array('password'));
		//get session retry vars
		$this->session->setReturnValueAt(0,'getArrayKey',null,array('tmLibAuthRetryCount',$this->login));
		$this->session->setReturnValueAt(1,'getArrayKey',1,array('tmLibAuthRetryCount',$this->login));
		$this->session->setReturnValueAt(2,'getArrayKey',2,array('tmLibAuthRetryCount',$this->login));
		//expect session retry vars
		$this->session->expectAt(0,'setArrayKey',array(SESSION_RETRY_COUNT,$this->login,1));
		$this->session->expectAt(1,'setArrayKey',array(SESSION_RETRY_COUNT,$this->login,2));
		$this->session->expectAt(2,'setArrayKey',array(SESSION_RETRY_COUNT,$this->login,3));
		$this->session->expectCallCount('setArrayKey',3);		
		//execute 3 wrong calls
		$this->assertFalse($auth->confirmAuth($this->request,$this->val));
		$this->assertFalse($auth->confirmAuth($this->request,$this->val));
		$this->assertFalse($auth->confirmAuth($this->request,$this->val)); 
		//set session retry to max
		$this->session->setReturnValue('getArrayKey',3);
		//execute another call
		try
		{
			$this->assertFalse($auth->confirmAuth($this->request,$this->val));
			$this->session->tally();
			$this->fail('AuthExcpetion not thrown as expected');
			
		}
		catch (AuthException $e) {
			$this->session->tally();
			$this->pass('Excpetion thrown as expected');
		}
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfAuth');
	$test->addTestCase(new TestOfAuth);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>