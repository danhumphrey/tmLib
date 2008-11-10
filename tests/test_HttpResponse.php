<?php
if (! defined('SIMPLE_TEST')) {
        define('SIMPLE_TEST', 'C:\\Users\\Dan\\dev\\simpletest\\');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

//main tmLib includes file
require_once('../includes.php');

class TestOfHttpResponse extends WebTestCase {
	function __construct() {
		parent::WebTestCase();
	}
	function testSetAndGetContent() {
		$res = new HttpResponse();
		$content = 'This is some content';
		$res->setContent($content);
		$this->assertEqual($content,$res->getContent());
	}
	function testSetAndGetRedirect() {
		$res = new HttpResponse();
		$redirect = 'http://www.google.com';
		$res->setRedirect($redirect);
		$this->assertEqual($redirect,$res->getRedirect());
	}
	function testSetAndGetHeaders() {
		$res = new HttpResponse();
		$res->setHeader('Cache-Control: no-cache, must-revalidate');
		$headers = $res->getHeaders();
		$this->assertEqual(sizeof($headers),1);
		$this->assertEqual('Cache-Control: no-cache, must-revalidate',$headers[0]);
		$res->setHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		$headers = $res->getHeaders();
		$this->assertEqual(sizeof($headers),2);
		$this->assertEqual('Expires: Mon, 26 Jul 1997 05:00:00 GMT',$headers[1]);
	}
	function testExecuteContent() {
		$res = new HttpResponse();
		$content = 'This is some content';
		$res->setContent($content);
		$this->assertEqual($content,$res->execute());
	}
	function testExecuteHeaders() {
		$this->get('web_test_http_response_headers.php');
		$this->assertText('Test HttpResponse header');
		$this->assertHeader('Cache-Control', 'no-cache, must-revalidate');
		$this->assertHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
	}
	function testExecuteRedirect() {
		$this->setMaximumRedirects(0);
		$this->get('http://localhost/tmLib/tests/web_test_http_response_redirect.php');
		$this->assertResponse(array(301, 302, 303, 307));
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfHttpResponse');
	$test->addTestCase(new TestOfHttpResponse);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>