<?php
error_reporting (E_ALL);
// Include the PathVars class
require_once('../classes/tmLib.php');
TmLib::init();
AutoLoad::setCacheFile('cache.inc');

function __autoload($className) {
	AutoLoad::loadClass($className);
}

$req = new HttpRequest();
$res = new HttpResponse();

$fc = new FrontController(new PageMapper('pages/'));

if($fc->execute($req,$res))
{
	echo $res->execute();
}

?>