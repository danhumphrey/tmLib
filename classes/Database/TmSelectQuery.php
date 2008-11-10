<?php
/**
 * Contains the TmSelectQuery class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for preparing Select Query statements
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class TmSelectQuery extends TmWhereQuery {
	
	/**
	 * Constant for Ascending sorts
	 *
	 */
	const ASC = 'ASC';
	/**
	 * Constant for Descending sorts
	 *
	 */
	const DESC = 'DESC';
	
	protected $groupBy;
	protected $orderBy;

	/**
	 * Adds the column(s) to the Select Query
	 *
	 * @param mixed $columns a comma separated string of column name(s) or an array of column name(s)
	 * @return TmSelectQuery returns a pointer to $this
	 */
	function select($columns = '*') {
		$this->addColumns($columns);
		return $this;
	}
	/**
	 * Adds table(s) to the Select Query
	 *
	 * @param mixed $tables a comma separated string of table name(s) or an array of table name(s)
	 * @return TmSelectQuery returns a pointer to $this
	 */
	function from($tables) {
		$this->addTables($tables);
		return $this;
	}
	/**
	 * Returns a subselect query for In conditions
	 * 
	 * @return TmSubSelectQuery
	 */
	function subSelect() {
		return new TmSubSelectQuery($this);
	}
	/**
	 * Adds a Group By statement to the query
	 *
	 * @param mixed $fields a comma separated string or an array of table columns or expressions 
	 * to group by
	 * @param mixed $having if not null, adds a Having condition to the Group By statement
	 * @return TmSelectQuery returns a pointer to $this
	 */
	function groupBy($fields, $having = null) {
		if(is_array($fields)) {
			$fields = join(', ', $fields);
		}
		//if group by already used append with comma
		if(isset($this->groupBy)) {
			$this->groupBy .=', ';
		}
		$this->groupBy .= $fields;
		//add having clause
		if($having !== null) 
		{
			$this->having($having);
		}
		return $this;
	}
	//only called by group by
	private function having($args) {
		if(is_array($args)) {
			$args = join(' AND ', $args);
		}
		$this->groupBy .= ' HAVING ' .$args;
		return $this;
	}
	/**
	 * Adds an Order By statement to the query
	 *
	 * @param string $field a the column or expression name to order by
	 * @param const $type (optional) a constant defining the order type - defaults to Ascending
	 * @return TmSelectQuery returns a pointer to $this
	 */
	function orderBy($field, $type = self::ASC) {
		//if order by already used append with comma
		if(isset($this->orderBy)) {
			$this->orderBy .=', ';
		}
		$this->orderBy .= $field . " $type";
		return $this;
	}
	
	/**
	 * Called internally by getSql to build the sql string
	 * @internal
	 * @see TmQuery::getSql();
	 */
	protected function generateSql() {
		$this->sql = 'SELECT '.$this->columns().' FROM '.$this->tables;
		//add where criteria
		if(isset($this->where)) {
			$this->sql .= ' WHERE ' . $this->where;
		}
		//add group by criteria
		if(isset($this->groupBy)) {
			$this->sql .= ' GROUP BY ' . $this->groupBy;
		}
		//add order by criteria
		if(isset($this->orderBy)) {
			$this->sql .= ' ORDER BY ' . $this->orderBy;
		}
		//add limit criteria
		if(isset($this->limit)) {
			$this->sql .= $this->limit;
		}
	}
	
}
?>