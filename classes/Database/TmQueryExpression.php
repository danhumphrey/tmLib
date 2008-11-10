<?php
/**
 * Contains the TmQueryExpression class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing query expression statements for modfiying, joining and performing math on table data
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmQueryExpression {
	//* Math functions
	/**
	 * Creates a mathematical Addition statement
	 * Accepts multiple parameters and joins them with +
	 * 
	 * @return string the statement
	 */
	function add() {
		$args = func_get_args();
		return '(' .join('+',$args) .')';
	}
	/**
	 * Creates a mathematical Subtraction statement
	 * Accepts multiple parameters and joins them with -
	 *
	 * @return string the statement
	 */
	function subtract() {
		$args = func_get_args();
		return '(' .join('-',$args) .')';
	}
	/**
	 * Creates a mathematical Multiplication statement
	 * Accepts multiple parameters and joins them with *
	 *
	 * @return string the statement
	 */
	function multiply() {
		$args = func_get_args();
		return '(' .join('*',$args) .')';
	}
	/**
	 * Creates a mathematical Division statement
	 * Accepts multiple parameters and joins them with /
	 *
	 * @return string the statement
	 */
	function divide() {
		$args = func_get_args();
		return '(' .join('/',$args) .')';
	}
	
	/**
	 * Creates a concatenation statement
	 *
	 * @param mixed $fields a comma separated string or an array of fields
	 * @return string the statement
	 */
	function concat($fields) {
		if(is_array($fields)) {
			$fields = join( ', ', $fields );
		}
		return "CONCAT($fields)";
	}
	/**
	 * Creates a Substring statement
	 *
	 * @param string $value the expression to perform the substring on
	 * @param int $start the start position of the substring operation
	 * @param mixed $length (optional) if not null, the length of the substring operation
	 * @return string the statement
	 */
	function substr($value, $start, $length = null) {
		if($length === null) {
			return "substring($value from $start)";
		}
		else {
			return "substring($value from $start for $length)";
		}
	}
	/**
	 * Creates a Count statement
	 * 
	 * @param string $field the table column or expression to count
	 * @return string the statement
	 */
	function count($field) {
		return "COUNT($field)";
	}
	/**
	 * Creates a Min statement
	 *
	 * @param string $field the table column or expression to Min
	 * @return string the statement
	 */
	function min($field) {
		return "MIN($field)";
	}
	/**
	 * Creates a Max statement
	 *
	 * @param string $field the table column or expression to Max
	 * @return string the statement
	 */
	function max($field) {
		return "MAX($field)";
	}
	/**
	 * Creates an Avg statement
	 *
	 * @param string $field the table column or expression to Avg
	 * @return string the statement
	 */
	function avg($field) {
		return "AVG($field)";
	}
	/**
	 * Creates a Sum statement
	 *
	 * @param string $field the table column or expression to Sum
	 * @return string the statement
	 */
	function sum($field) {
		return "SUM($field)";
	}
	/**
	 * Creates a Round statement
	 *
	 * @param string $field the table column or expression to Round
	 * @param int $decimals the number of decimal places to Round to
	 * @return string the statement
	 */
	function round($field,$decimals) {
		return "ROUND($field, $decimals)";
	}
	/**
	 * Creates a Mod statement
	 *
	 * @param string $field1 the first table column or expression
	 * @param string $field1 the second table column or expression
	 * @return string the statement
	 */
	function mod($field1, $field2) {
		return "MOD($field1, $field2)";
	}
	/**
	 * Creates a Length statement
	 *
	 * @param string $field the table column or expression to evaluate the length of
	 * @return string the statement
	 */
	function length($field) {
		return "LENGTH($field)";
	}
	/**
	 * Creates an Md5 statement
	 *
	 * @param string $field the table column or expression to Md5
	 * @return string the statement
	 */
	function md5($field) {
		return "MD5($field)";
	}
	/**
	 * Creates a Now statement
	 *
	 * @return string the statement
	 */
	function now() {
		return "NOW()";
	}
}
?>