<?php
error_reporting (E_ALL);

// tmLib
require_once('../classes/tmLib.php');
TmLib::init();
AutoLoad::setCacheFile('cache.inc');

function __autoload($className) {
	AutoLoad::loadClass($className);
}
$req = new HttpRequest();
$res = new HttpResponse();

$fc = new FrontController(new CommandMapper('commands/'));

if($fc->execute($req, $res))
{
	echo $res->execute();
} else {
	echo 'No Command Found';
}

?>