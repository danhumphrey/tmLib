<?php
class AuthUserValidator implements IAuthValidator {
	function validateAuth($req, $def) {
		
		//get req vars
		$login = $req->get($def->getLoginVar());
		$pw = $req->get($def->getPasswordVar());
		
		if($login == 'admin' && $pw == 'admin') {
			return true;
		}
	}
}
?>