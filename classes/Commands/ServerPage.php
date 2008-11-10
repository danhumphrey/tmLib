<?php
/**
  * ServerPage class
  * 
  * @package tmLib
  * @subpackage Commands
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * ServerPage class is a 'Command' that adds an included php page to the HttpResponse
 * 
 * @package tmLib
 * @subpackage Commands
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class ServerPage implements IRequestHandler  
{
	private $page;
	/**
	 * Constructs the ServerPage
	 *
	 * @param string $page the page to include
	 */
	function __construct($page)
	{
		$this->page = $page;
	}
	
	/**
	 * executes the command
	 *
	 * @param object HttpRequest $request
	 * @param object HttpResponse $response
	 */
	function execute($request, $response)
	{
		ob_start();
        include($this->page);
        $response->setContent(ob_get_clean());
	}
}
?>