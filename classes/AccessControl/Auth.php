<?php
/**
  * Auth class
  *
  * @package tmLib
  * @subpackage AccessControl
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * Auth Class provides the authorization of logged in users
 *
 * @package tmLib
 * @subpackage AccessControl
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class Auth {
	
	private $definition;
	private $session;
	const SESS_HASH_NAME = 'tmLibAuthSessionHash';
	const SESS_RETRY_NAME = 'tmLibAuthRetryCount';
	
	/**
	 * Constructs the Auth class
	 *
	 * @param Session $session a Session object
	 * @param AuthDefinition $definition an Auth Definition object defining the auth vars
	 */
	function __construct($session, $definition) {
		$this->session = $session;
		$this->definition = $definition;
	}
	
	/**
	 * Confirms Auth based on a HTTPRequest and an AuthValidator
	 *
	 * @param HttpRequest $request
	 * @param IAuthValidator $validator an auth validator to validate the login and password
	 * @return bool false if not confirmed
	 * @throws AuthException if retryCOunt exceeded
	 */
	function confirmAuth($request, $validator) {
		//validate session if it exists
		
		if($this->session->get(self::SESS_HASH_NAME) != null) {
			return $this->validateSession();
		}
		//pass the request to the validator and attempt to validate user credentials
		if($validator->validateAuth($request, $this->definition)) {
			//validation passed
			$this->storeAuth($request->get($this->definition->getLoginVar()),$request->get($this->definition->getPasswordVar()));
			$this->session->remove(self::SESS_RETRY_NAME); //remove our retry count
			return true;
		}
		//are we monitoring the retry count?
		if($c = $this->definition->getRetryCount()) {
			//get retry count session var and add 1
			$count = (int) $this->session->getArrayKey(self::SESS_RETRY_NAME,$request->get($this->definition->getLoginVar())) + 1;
			if($count>$this->definition->getRetryCount()) {
				//retry count exceeded throw exception
				throw new AuthException('Invalid login attempts exceeded ' .$this->definition->getRetryCount());
				return;				
			}
			//set new (incremented) retry count
			$this->session->setArrayKey(self::SESS_RETRY_NAME,$request->get($this->definition->getLoginVar()),$count);
		}
	}
	
	/**
    * Logs out and clears the Auth session
    * @return void
    */
	function logout() {
		$this->session->remove($this->definition->getLoginVar());
		$this->session->remove($this->definition->getPasswordVar());
		$this->session->remove(self::SESS_HASH_NAME);
	}
	
	/**
    * Sets the session variables after a successful login
    * @param string $login
    * @param string $password
    * @return void
    * @access private
    */
    private function storeAuth($login,$password) {
        $this->session->set($this->definition->getLoginVar(),$login);
        $password = $this->definition->useMd5() ? md5($password) : $password;
        $this->session->set($this->definition->getPasswordVar(),$password);
        $hashKey = md5($this->definition->getHashKey().$login.$password);
        $this->session->set(self::SESS_HASH_NAME,$hashKey);
    }
    
    /**
    * Validates the session variables
    * @return bool false if invalid
    * @access private
    */
    private function validateSession() {
        $login = $this->session->get($this->definition->getLoginVar());
        $password = $this->session->get($this->definition->getPasswordVar());
        $hashKey = $this->session->get(self::SESS_HASH_NAME);
        if (md5($this->definition->getHashKey().$login.$password) == $hashKey ) {
            return true;
        }
    }
}
?>