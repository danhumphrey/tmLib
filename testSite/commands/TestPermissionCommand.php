<?php
class TestPermissionCommand extends AuthorizedCommand {
	
	function __construct() {
		$this->perm = 'ADVANCED';
	}
	function execute($request, $response) {
		if(parent::execute($request, $response)) {
			$response->setContent('TestPermissionCommand executed');
			return true;
		}
	}
}
?>