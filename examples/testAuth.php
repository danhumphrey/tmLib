<?php
error_reporting (E_ALL);
// Include the tmLib library
require_once('../classes/tmLib.php');
//tmLib
TmLib::init();
AutoLoad::setCacheFile('cache.inc');
//setup autoload
function __autoload($className) {
	AutoLoad::loadClass($className);
}
class MyValidator implements IAuthValidator {
	function validateAuth($req,$def) {
		if($req->get($def->getLoginVar()) == 'admin') {
			if($req->get($def->getPasswordVar()) == 'letmein') {
				return true;
			}
		}
	}
}
try {
	//Http
	$request = new HttpRequest();
	$response = new HttpResponse();
	//use authentication
	$def = new AuthDefinition('login','password','secret');
	$auth = new Auth(new Session(),$def,false);
	if (!$auth->confirmAuth($request,new MyValidator())) {
		echo 'not permitted';
		exit;
	}
	//front controller
	$fc = new Dispatcher(new PageMapper('pages/'));
	if($fc->execute($request,$response)) {
		echo $response->execute();
	}
}
catch (AuthException $e1) {
}
catch (Exception $e2) {
}
?>