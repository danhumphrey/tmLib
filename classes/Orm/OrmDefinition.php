<?php
/**
  * OrmDefinition class
  * 
  * @package tmLib
  * @subpackage Orm
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * OrmDefinition holds orm mapping data about an OrmObject
 * 
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class OrmDefinition {
	
	private $class;
	private $table;
	private $columns = array();
	private $relations = array();

	/**
	 * Constructs the OrmDefinition
	 *
	 * @param class $class the class name of the OrmObject
	 * @param string $table the table name in the database
	 * @param array $columns (optional) an array of columns 
	 * @param array $relations (optional) an array of relations
	 */
	function __construct($class, $table, array $columns, array $relations = array()) {
		$this->class = $class;
		$this->table = $table;
		$this->columns = $columns;
		$this->relations = $relations;
	}
	/**
	 * Gets the name of the OrmObject class
	 *
	 * @return string the name of the class
	 */
	function getClass() {
		return $this->class;
	}
	/**
	 * Gets the name of the database table
	 *
	 * @return string the name of the table
	 */
	function getTable() {
		return $this->table;
	}
	
	/**
	 * Gets an array of the OrmObject columns
	 *
	 * @return array an array of column definitions
	 */
	function getColumns() {
		return $this->columns;
	}
	
	/**
	 * Gets an array of the OrmObject relations
	 *
	 * @return array an array of relations
	 */
	function getRelations() {
		return $this->relations;
	}
}
?>