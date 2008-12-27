<?php
require_once('inc.php');
try {
	$dispatcher = new Dispatcher(new CommandMapper(CMD_PATH));
	$response = new HttpResponse();
	if(!$dispatcher->execute(new HttpRequest(),$response)){
		//default command
		$response->setContent('Default response - no command executed');
	}
	// output repsonse
	echo $response->execute();
	
}
catch(AuthRequiredException $e) {
	echo 'Do Login  - Auth Required';
}
catch(PermissionRequiredException $e) {
	echo 'Do Login  - Permission Required';
}
catch(Exception $e) {
	echo 'an exception occured - '. $e->getMessage();
}
?>