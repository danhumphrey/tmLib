<?php
/**
  * IInputProcessor Interface
  * 
  * @package tmLib
  * @subpackage Input
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * IInputProcessor should be implemented by all classes that 'process' the input data
 * 
 * @package tmLib
 * @subpackage RequestMappers
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
interface IInputProcessor {
	
	Const PROCESS_CONTINUE = 1;
	Const PROCESS_STOP = 2;
	
	/**
	 * processes the data
	 *
	 * @param object DataSet $dataSet
	 */
	public function process($dataSet);
}
?>