<?php
require_once('../includes.php');


$res = new HttpResponse();
$res->setHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
$res->setHeader('Cache-Control: no-cache, must-revalidate');

$res->execute();


//header('Cache-Control: no-cache, must-revalidate');
?>
<h1>Test HttpResponse header</h1>