<?php
//tests includes
require_once('test-includes.php');


class TestOfHttpAjaxResponse extends WebTestCase {
	function __construct() {
		parent::WebTestCase();
	}
	function testSetAndGetContent() {
		$res = new HttpAjaxResponse();
		$content = array('This is some content');
		$res->setContent($content);
		$this->assertEqual($content,$res->getContent());
	}
	function testSetAndGetRedirect() {
		$res = new HttpAjaxResponse();
		$redirect = 'http://www.google.com';
		$res->setRedirect($redirect);
		$this->assertEqual($redirect,$res->getRedirect());
	}
	function testSetAndGetHeaders() {
		$res = new HttpAjaxResponse();
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
		$res = new HttpAjaxResponse();
		$content = array('This is some content');
		$res->setContent($content);
		$this->assertEqual(json_encode($content),$res->execute());
	}
	
	function testSetAndGetKey() {
		$res = new HttpAjaxResponse();
		$res->setKey('key1', 'val1');
		$this->assertEqual('val1',$res->getKey('key1'));
	}
	
	function testExecuteWithKeys() {
		$res = new HttpAjaxResponse();
		$content = array('key1'=>'val1','key2'=>'val2');
		$res->setKey('key1', 'val1');
		$res->setKey('key2', 'val2');
		$this->assertEqual(json_encode($content),$res->execute());
	}
	function testExecuteHeaders() {
		$this->get('http://localhost/tmLib/tests/web_test_http_response_headers.php');
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
	$test = new GroupTest('TestOfHttpAjaxResponse');
	$test->addTestCase(new TestOfHttpAjaxResponse);
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>