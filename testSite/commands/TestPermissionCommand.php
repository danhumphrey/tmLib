<?php
class TestPermissionCommand extends AuthorizedCommand {
	
	function __construct() {
		$this->perm = 'BASIC';
	}
	function execute($request, $response) {
		if(parent::execute($request, $response)) {
			$response->setContent('TestPermissionCommand executed');
			return true;
		}
	}
}
?>