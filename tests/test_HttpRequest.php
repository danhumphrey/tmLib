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

//base UnitTestCase for testing $_GET data
abstract class HttpRequestGetTestCase extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
	}
	function tearDown() {
		unset($_GET);
	}
}
//$_GET tests
class TestOfHttpRequestGet extends HttpRequestGetTestCase  {
	function __construct() {
		parent::UnitTestCase();
	}
	function testInvalidKeyExistsReturnsFalse() {
		$req = new HttpRequest();
		$this->assertFalse($req->keyExists('testkey'));
	}
	function testValidGetKeyExistsReturnsTrue() {
		$_GET['testGetKey'] = 'testGetValue';
		$req = new HttpRequest();
		$this->assertTrue($req->keyExists('testGetKey'));
	}
	function testIsPostReturnsFalseForGetMethod() {
		$_GET['testGetKey'] = 'testGetValue';
		$req = new HttpRequest();
		$this->assertFalse($req->isPost());
	}
	function testValidGetKeyContainsCorrectValue() {
		$_GET['testGetKey'] = 'testGetValue';
		$req = new HttpRequest();
		$this->assertEqual($req->get('testGetKey'), 'testGetValue');
	}
	function testInvalidGetKeyContainsNullValue() {
		$req = new HttpRequest();
		$this->assertNull($req->testNullKey);
	}
	function testGetVarsHaveSlashesStripped() {
		$_GET['testGetKey'] = "These\\ slashes stripped";
		$req = new HttpRequest();
		$this->assertEqual($req->get('testGetKey'), "These slashes stripped");
	}
	function testSetMethod() {
		$req = new HttpRequest();
		$req->set('setKey', 'setValue');
		$this->assertEqual($req->get('setKey'),'setValue');
	}
}
//base UnitTestCase for testing $_POST data
abstract class HttpRequestPostTestCase extends UnitTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {
		$_SERVER['REQUEST_METHOD'] = 'POST';
	}
	function tearDown() {
		unset($_POST);
	}
}
//$_POST tests
class TestOfHttpRequestPost extends HttpRequestPostTestCase {
	function __construct() {
		parent::UnitTestCase();
	}
	function testValidPostKeyExistsReturnsTrue() {
		$_POST['testPostKey'] = 'testPostValue';
		$req = new HttpRequest();
		$this->assertTrue($req->keyExists('testPostKey'));
	}
	function testIsPostReturnsTrueForPostMethod() {
		$_POST['testPostKey'] = 'testPostVal';
		$req = new HttpRequest();
		$this->assertTrue($req->isPost());
	}
	function testInvalidPostKeyContainsNullValue() {
		$req = new HttpRequest();
		$this->assertNull($req->get('testNullKey'));
	}
	function testValidPostKeyContainsCorrectValue() {
		$_POST['testPostKey'] = 'testPostValue';
		$req = new HttpRequest();
		$this->assertEqual($req->get('testPostKey'), 'testPostValue');
	}
	function testPostVarsHaveSlashesStripped() {
		$_POST['testPostKey'] = "These\\ slashes stripped";
		$req = new HttpRequest();
		$this->assertEqual($req->get('testPostKey'), "These slashes stripped");
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfHttpRequest');
	$test->addTestCase(new TestOfHttpRequestGet());
	$test->addTestCase(new TestOfHttpRequestPost);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>