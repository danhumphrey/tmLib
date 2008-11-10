<?php
/**
 * Contains the TmQueryCriteria class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing query criteria statements for comparison and selection of data
 * 
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmQueryCriteria {
	/**
	 * Creates an Equal statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must equal
	 * @return string the statement
	 */
	function eq($field, $value) {
		return "$field = $value";
	}
	/**
	 * Creates a Not Equal statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must not equal
	 * @return string the statement
	 */
	function ne($field, $value) {
		return "$field <> $value";
	}
	/**
	 * Creates a Less Than statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must be less than
	 * @return string the statement
	 */
	function lt($field, $value) {
		return "$field < $value";
	}
	/**
	 * Creates a Less Than Or Equal To statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must be less than or equal to
	 * @return string the statement
	 */
	function lteq($field, $value) {
		return "$field <= $value";
	}
	/**
	 * Creates a Greater Than statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must be greater than
	 * @return string the statement
	 */
	function gt($field, $value) {
		return "$field > $value";
	}
	/**
	 * Creates a Greater Than Or Equal To statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must be greater than or equal to
	 * @return string the statement
	 */
	function gteq($field, $value) {
		return "$field >= $value";
	}
	/**
	 * Creates a Like statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $value the value that the field must be like
	 * @return string the statement
	 */
	function like($field, $value) {
		return "$field LIKE $value";
	}
	
	/**
	 * Creates a between statement
	 *
	 * @param string $field the column or exression to compare
	 * @param mixed $value1 the lower boundary of the between
	 * @param mixed $value2 the upper boundary of the between
	 * @return string the statement
	 */
	public function between($field, $value1, $value2 ) {
		return "$field BETWEEN $value1 AND $value2";
	}
	/**
	 * Creates an In statement
	 *
	 * @param string $field the column or expression to compare
	 * @param mixed $values a comma separated string, an array, or a subquery that the field must be in
	 * @return string the statement
	 */
	public function in($field, $values){
		if(is_array($values)) {
			$values = join( ', ', $values );
		}
		return "$field IN ($values)";
	}
	/**
	 * Creates a Not statement
	 *
	 * @param string $field the column or expression to evaluate
	 * @return string the statement
	 */
	function not($field) {
		return "NOT $field";
	}
	/**
	 * Creates an isNull statement
	 *
	 * @param string $field the column or expression to evaluate
	 * @return string the statement
	 */
	public function isNull($field) {
		return "$field IS NULL";
	}
	/**
	 * Creates a Logical And statement
	 * Accepts multiple parameters and joins them with AND
	 * 
	 * @return string the statement
	 */
	function lAnd() {
		$args = func_get_args();
		return '( ' .join(' AND ', $args) .' )';
	}
	/**
	 * Creates a Logical Or statement
	 * Accepts multiple parameters and joins them with OR
	 *
	 * @return string the statement
	 */
	function lOr() {
		$args = func_get_args();
		return '( ' .join(' OR ', $args) . ' )';
	}
}
?>