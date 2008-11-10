<?php
/**
 * Contains the TmSubSelectQuery class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing SubSelect Query statements
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmSubSelectQuery extends TmSelectQuery {
	
	/**
	 * @var TmSelectQuery
	 */
	private $parent;
	
	/**
	 * Constructs the TmSubSelectQuery
	 *
	 * @param TmSelectQuery $parent the parent query
	 */
	function __construct($parent) {
		$this->parent = $parent;
	}
	/**
	 * Binds a parameter to the parent query
	 *
	 * @param mixed $param a pointer to the paramater
	 * @param string $paramName (optional) the name of the bound parameter, a parameter name will
	 * be generated if not specified
	 * @return returns the bound paramater name
	 */
	function bindParam(&$param, $paramName = null) {
		return $this->parent->bindParam($param, $paramName);
	}
	/**
	 * Binds a value to the parent query
	 *
	 * @param mixed $value the value to bind
	 * @param string $valueName (optional) the name of the bound value, a value name will
	 * be generated if not specified
	 * @return returns the bound value name
	 */
	function bindValue(&$value, $valueName = null) {
		return $this->parent->bindValue($value, $valueName);
	}
	
	/**
	 * Called internally by getSql to build the sql string
	 * @internal
	 * @see TmQuery::getSql();
	 */
	protected function generateSql() {
		parent::generateSql();
	}
	/**
	 * Returns a subselect query for In conditions
	 *
	 * @return TmSubSelectQuery
	 */
	function subSelect() {
		return new TmSubSelectQuery($this->parent);
	}
}
?>