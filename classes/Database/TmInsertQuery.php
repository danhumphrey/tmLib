<?php
/**
 * Contains the TmInsertQuery class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing Insert Query statements
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmInsertQuery extends TmQuery {
		
	protected $values = array();
	
	/**
	 * Adds the table to the Insert Query
	 *
	 * @param string $table the table name to insert into
	 * @return TmInsertQuery returns a pointer to $this
	 */
	function insertInto($table) {
		$this->addTables($table);
		return $this;
	}
	/**
	 * Sets a column and value pair
	 *
	 * @param string $column the column name
	 * @param mixed $val the value of the column
	 * @return TmInsertQuery returns a pointer to $this
	 */
	function set($column, $val) {
		if(is_string($val)) {
			$val = $this->db->quote($val);
			$this->values[$column] = $val;	
		}
		else
		{
			$this->values[$column] = $val;
		}
		return $this;
	}

	/**
	 * Called internally by getSql
	 * @internal 
	 * @see TmQuery::getSql();
	 */
	protected function generateSql() {
		$this->sql = 'INSERT INTO '.$this->tables;
		$columns = implode( ', ', array_keys( $this->values ) );
		$values = implode( ', ', array_values( $this->values ) );
		$this->sql .= " ($columns) VALUES ($values)";
	}
	
}
?>