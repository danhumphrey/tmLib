<?php
/**
  * TmLib class
  *
  * @package tmLib
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * TmLib is the baseline class that should be included by all projects using the tmLib library
 *
 * @package tmLib
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class TmLib {

	/**
	 * init MUST be called by all projects using the tmLib library
	 *
	 */
	static function init() {
		$dirBase = dirname(__FILE__);
		//define('TMLIB_PATH', $dirBase);
		require_once ('Core/AutoLoad.php');
		AutoLoad::addClassPath($dirBase);
	}
}
?>