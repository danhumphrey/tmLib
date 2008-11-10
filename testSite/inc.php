<?php
error_reporting (E_ALL);
ini_set('include_path','./:'.ini_get('include_path'));

define ('DIRSEP', DIRECTORY_SEPARATOR);
define ('SITE_PATH', realpath(dirname(__FILE__).DIRSEP).DIRSEP);
define ('CMD_PATH', SITE_PATH.'commands'.DIRSEP);

/* TmLib include and setup */
/* ----------------------- */
require_once('../classes'.DIRSEP.'TmLib.php');

TmLib::init();

//-autoloading
AutoLoad::setCacheFile(SITE_PATH.'cache'.DIRSEP.'autoload.cache');
AutoLoad::addClassPath(SITE_PATH.'classes');
function __autoload($className) {
	AutoLoad::loadClass($className);
}
//-auth definitions
define ('AUTH_LOGIN_VAR','login');
define('AUTH_PASSWORD_VAR','password');
define('AUTH_HASH','somesecrethashkey');
/* ----------------------- */
?>