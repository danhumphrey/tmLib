<?php
require_once('../includes.php');

$res = new HttpResponse();
$redirect = 'web_test_http_response_redirected.php';
$res->setRedirect($redirect);
$res->execute();
?>
<h1>Test HttpResponse redirect</h1>
