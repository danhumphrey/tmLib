<?php
/**
  * PhpTalView class
  * 
  * @package tmLib
  * @subpackage Views
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * PhpTalView is template view used with PHPTAL
 * 
 * @package tmLib
 * @subpackage Views
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
abstract class PhpTalView  {
	/**
	 * Template System
	 *
	 * @var object PHPTal template system
	 */
	protected $template;
	protected $templateFile;
	
	/**
	 * Constructs the PhpTalView
	 *
	 * @param string $templateFile the template file used for generation
	 */
	function __construct($templateFile) {
		$this->templateFile = $templateFile;
		$this->template = new PHPTAL($this->templateFile);
	}
	/**
	 * Generates the View
	 *
	 * @param IDataSet $dataSet the dataset
	 * @param HttpResponse $response the response
	 */
	abstract function generate($dataSet, $response);
}
?>