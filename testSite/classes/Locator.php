<?php
class Locator extends Registry {
	/**
	 * Get Session
	 *
	 * @return Session
	 */
	static function getSession(){
		//lazy load the session component
		if(Locator::keyExists('SESSION')) {
			return Locator::get('SESSION');
		}else {
			$session = new Session();
			Locator::set('SESSION',$session);
			return $session;
		}
	}
	
	/**
	 * Get AuthDefinition
	 *
	 * @return AuthDefinition
	 */
	static function getAuthDefinition(){
		//lazyload auth definition component
		if(Locator::keyExists('AUTH_DEFINITION')) {
			return Locator::get('AUTH_DEFINITION');
		}else {
			$def = new AuthDefinition(AUTH_LOGIN_VAR,AUTH_PASSWORD_VAR,AUTH_HASH);
			Locator::set('AUTH_DEFINITION',$def);
			return $def;
		}
	}
	
	/**
	 * Get Auth
	 *
	 * @return Auth
	 */
	static function getAuth(){
		//lazyload auth component
		if(Locator::keyExists('AUTH')) {
			return Locator::get('AUTH');
		}else {
			$session = Locator::getSession();
			$def = Locator::getAuthDefinition();
			$auth = new Auth($session,$def);
			Locator::set('AUTH',$auth);
			return $auth;
		}
	}
	
	/**
	 * Get CurrentUser
	 *
	 * @return CurrentUser
	 */
	static function getCurrentUser(){
		//lazyload CurrentUser component
		if(Locator::keyExists('CURRENT_USER')) {
			return Locator::get('CURRENT_USER');
		}else {
			$session = Locator::getSession();
			$def = Locator::getAuthDefinition();
			$user = new CurrentUser($session->get($def->getLoginVar()));
			Locator::set('CURRENT_USER',$user);
			return $user;
		}
	}
}
?>
