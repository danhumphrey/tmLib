<?php
/**
 * Contains the TmWhereQuery class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Abstract Class for preparing Where Query statements
 *
 * @package tmLib
 * @subpackage Database
 * @abstract
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
abstract class TmWhereQuery extends TmQuery {
	protected $where;
	protected $limit;
	
	private $criteria;
	/**
	 * Constructs the Query
	 *
	 * @param TmDb $db the database instance
	 */
	function __construct($db) {
		$this->criteria = new TmQueryCriteria();
		parent::__construct($db);
	}
	/**
	 * Adds a where condition to the query
	 * Accepts multiple paramaters and joins them with and
	 * 
	 * @return TmWhereQuery a reference to $this
	 */
	function where() {
		$args = func_get_args();
		//if where already used append with AND
		if(isset($this->where)) {
			$this->where .=' AND ';
		}
		$this->where .= join(' AND ', $args);
		return $this;
	}
	/**
	 * Adds a limit clause
	 *
	 * @param int $limit the number to limit to
	 * @param int $offset the offset number
	 * @return TmWhereQuery a reference to $this
	 */
	function limit($limit, $offset = 0) {
		if ($offset === 0)	{
			$this->limit = " LIMIT $limit";
		}
		else {
			$this->limit = " LIMIT $limit OFFSET $offset";
		}
		return $this;
	}
	/**
	 * Returns the query criteria object for WHERE criteria
	 *
	 * @return TmQueryCriteria
	 */
	function criteria() {
		return $this->criteria;
	}
	
}
?>