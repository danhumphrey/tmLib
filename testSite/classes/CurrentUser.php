<?php
class CurrentUser {
	
	private $login;
	
	function __construct($login) {
		$this->login = $login;
	}
	
	function hasPermission($perm) {
		if($perm == 'BASIC'){
			return true;
		} else {
			return false;
		}
	}
}
?>