<?php
error_reporting (E_ALL);

// tmLib
require_once('../classes/tmLib.php');
TmLib::init();
AutoLoad::setCacheFile('cache.inc');

function __autoload($className) {
	AutoLoad::loadClass($className);
}
$request = new HttpRequest();
$captcha = new Captcha(new Session());
if(!$captcha->validate($request,'captchaText')) {
	echo 'invalid';
}
?>