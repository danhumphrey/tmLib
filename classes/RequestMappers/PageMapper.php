<?php
/**
  * PageMapper Class
  * 
  * @package tmLib
  * @subpackage RequestMappers
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * PageMapper maps a HttpRequest to a ServerPage
 * 
 * @package tmLib
 * @subpackage RequestMappers
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class PageMapper extends BaseRequestMapper 
{
	private $pageDir = '';
	private $requestParameter = '';
	
	/**
	 * Enter description here...
	 *
	 * @param string $pageDir the directory where the pages are
	 * @param string $requestParameter the parameter to map
	 */
	function __construct($pageDir, $requestParameter = 'page')
	{
		$this->pageDir = $pageDir;
		$this->requestParameter = $requestParameter;
	}
	
	/**
     * Maps a request object to a ServerPage class 
     * @access public
     * @param object $request a HTTPRequest object
     * @return mixed an instance of the mapped ServerPage class or null
     */
	public function mapRequest($request)
	{
		$page = $this->untaint($request->get($this->requestParameter));
		$fileName = $this->pageDir . $page . '.php';
		if(is_readable($fileName))
		{
			return new ServerPage($fileName);				
		}
	}
}
?>