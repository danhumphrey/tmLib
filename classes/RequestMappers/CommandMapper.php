<?php
/**
  * CommandMapper Class
  * 
  * @package tmLib
  * @subpackage RequestMappers
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * CommandMapper maps a HttpRequest to a class that implements the IRequestHandler interface
 * 
 * @package tmLib
 * @subpackage RequestMappers
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class CommandMapper extends BaseRequestMapper {
	private $classPath = '';
	private $requestParameter = '';
	 /**
     * Constructs the CommandMapper class 
     * @access public
     * @param string $classPath the directory path to your command classes
     * @param string $requestParametr the name of the parameter to map
     */
	function __construct($classPath, $requestParameter = 'cmd'){
		$this->classPath = $classPath;
		$this->requestParameter = $requestParameter;
	}
	
	 /**
     * Maps a request object to a class implementing IRequestHandler
     * @access public
     * @param object $request a HTTPRequest object
     * @return mixed an instance of the mapped command class or null
     */
	public function mapRequest($request) {
		$command = $this->untaint($request->get($this->requestParameter));
		$fileName = $this->classPath . $command . 'Command.php';
		$className = str_replace('/', '_', $command);
		$className .= 'Command';
		if(file_exists($fileName))
		{
			include_once($fileName);
			if(class_exists($className))
			{
				return new $className;	
			}	
		}
	}
}
?>