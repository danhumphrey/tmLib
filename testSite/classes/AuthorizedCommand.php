<?php
class AuthorizedCommand extends BaseCommand {
	protected $perm = null;
	
	protected function canExecute() {
		//is authenticated?
		$auth = Locator::getAuth();	
		if(!$auth->confirmAuth($this->dataSet,new AuthUserValidator())) {
			throw new AuthRequiredException();
		}
		//requires permission to execute?
		if($this->perm !== null) {
			$user = Locator::getCurrentUser();
			if(!$user->hasPermission($this->perm)) {
				throw new PermissionRequiredException();
			}
		}
		return true;
	}
}
?>