<?php
/**
  * ICommandState interface
  * 
  * @package tmLib
  * @subpackage Commands
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * ICommandState defines the state methods that will be called on a command
 * when processed by an input processor
 * 
 * @package tmLib
 * @subpackage Commands
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @abstract 
 * @version 1.0
 */
interface ICommandState {
	/**
	 * The stateInitAction will be called when the command is in an 'init' state
	 *
	 */
	function stateInitAction();
	/**
	 * The stateValidAction will be called when the command is in a 'valid' state
	 *
	 */
	function stateValidAction();
	/**
	 * The stateInvalidAction will be called when the command is in a 'invalid' state
	 *
	 */
	function stateInvalidAction();	
}
?>