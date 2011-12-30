<?php
/**
 * Contains the TmUpdateQuery class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing Update Query statements
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmUpdateQuery extends TmWhereQuery {
		
	protected $values = array();
	
	/**
	 * Adds table(s) to the Update Query
	 *
	 * @param mixed $tables a comma separated string of table name(s) or an array of table name(s)
	 * @return TmUpdateQuery returns a pointer to $this
	 */
	function update($tables) {
		$this->addTables($tables);
		return $this;
	}
	/**
	 * Sets a column and value pair to update
	 *
	 * @param string $column the column name to update
	 * @param string $val the value to assign to the column
	 * @return TmUpdateQuery returns a pointer to $this
	 */
	function set($column, $val) {
		if(is_string($val) && !$this->hasBinding($val)) {
			$val = $this->db->quote($val);
		}
		$this->values[$column] = $val;
		return $this;
	}
	/**
	 * Called internally by getSql to build the sql string
	 * @internal
	 * @see TmQuery::getSql();
	 */
	protected function generateSql() {
		$this->sql = 'UPDATE '.$this->tables;
		//loop through columns and values
		if(sizeof($this->values)) {
			$set = null;
			foreach($this->values as $key => $val) {
				if($set !== null ) {
					$set .= ', ';
				}
				$set .= "$key = $val";
			}
			$this->sql .= ' SET ' .$set;
		}
		//add where criteria
		if(isset($this->where)) {
			$this->sql .= ' WHERE ' . $this->where;
		}
		//add limit criteria
		if(isset($this->limit)) {
			$this->sql .= $this->limit;
		}
	}
	
}
?>