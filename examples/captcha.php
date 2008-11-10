<?php
error_reporting (E_ALL);

// tmLib
require_once('../classes/tmLib.php');
TmLib::init();
AutoLoad::setCacheFile('cache.inc');

function __autoload($className) {
	AutoLoad::loadClass($className);
}

$captcha = new Captcha(new Session());
$captcha->generate();
?>