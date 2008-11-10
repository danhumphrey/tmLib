<?php
/**
 * Contains the TmDeleteQuery class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing Delete Query statements
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmDeleteQuery extends TmWhereQuery {
	
	/**
	 * Adds the table(s) to the Delete Query 
	 *
	 * @param mixed $tables a comma seperated string of table name(s) or an array of table name(s)
	 * @return TmDeleteQuery returns a pointer to $this
	 */
	function deleteFrom($tables) {
		$this->addTables($tables);
		return $this;
	}
	
	/**
	 * Called internally by getSql
	 * @internal 
	 * @see TmQuery::getSql();
	 */
	protected function generateSql() {
		$this->sql = 'DELETE FROM '.$this->tables;
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