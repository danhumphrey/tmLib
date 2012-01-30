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
 * @abstract 
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
abstract class TmQuery {	
	/**
	 * db connection
	 * @var TmPdoDb
	 */
	protected $db;

	private $bindsCounter = 0;

	private $boundParams = array();
	private $boundValues = array();

	protected $tables;

	private $columns = '';
	
	protected $sql;
	
	private $expression;
	
	private $className;
	
	/**
	 * Constructs the Query class
	 *
	 * @param TmDb $db the database instance
	 */
	function __construct($db) {
		$this->db = $db;
		$this->expression = new TmQueryExpression();
	}
	abstract protected function generateSql();
	

	/**
	 * Generates and returns an SQL statement
	 *
	 * @return string the generated SQL statement
	 */
	public function getSql() {
		$this->generateSql();
		return $this->sql;
	}
	
	
	/**
	 * Sets the class name
	 *
	 * @param string $className the class name
	 */
	public function setClassName($className) {
		$this->className = $className;
	}
	
	/**
	 * returns the class name
	 *
	 * @return string the class name
	 */
	public function getClassName() {
		return $this->className;
	}
	/**
	 * Returns the Query columns
	 *
	 * @return string a comma seperated string of column names or * if no columns specified
	 */
	public function columns() {
		if ($this->columns == '') {
			return '*';
		}
		return $this->columns;
	}
	
	/**
	 * Sets the query columns
	 *
	 * @param array $columns an array of column names
	 * @return mixed TmQuery a reference to $this
	 */
	function setColumns(array $columns) {
		$this->columns = join(', ',$columns);
		return $this;
	}
	
	/**
	 * Adds tables to the query
	 *
	 * @param mixed $tables a comma seperated string or an array of table names
	 */
	protected function addTables($tables) {
		if(is_array($tables)) {
			$tables = join(', ', $tables);
		}
		if($this->tables == '') {
			$this->tables = $tables;
		}
		else {
			$this->tables .= ', '.$tables;
		}
	}
	/**
	 * Adds columns to the query
	 *
	 * @param mixed $columns a comma seperated string or an array of column names
	 */
	protected function addColumns($columns) {
		if(is_array($columns)) {
			$columns = join(', ', $columns);
		}
		if($this->columns == '') {
			$this->columns = $columns;
		}
		else {
			$this->columns .= ', '.$columns;
		}
	}
	
	/**
	 * Binds a parameter to a query
	 *
	 * @param mixed $param a pointer to the paramater
	 * @param string $paramName (optional) the name of the bound parameter, a parameter name will 
	 * be generated if not specified
	 * @return returns the bound paramater name
	 */
	function bindParam(&$param, $paramName = null) {
		if($paramName === null) {
			$this->bindsCounter++;
			$paramName = ':bind'.$this->bindsCounter;
		}
		$this->boundParams[$paramName] = &$param;
		return $paramName;
	}
	/**
	 * Binds a value to a query
	 *
	 * @param mixed $value the value to bind
	 * @param string $valueName (optional) the name of the bound value, a value name will
	 * be generated if not specified
	 * @return returns the bound value name
	 */
	function bindValue(&$value, $valueName = null) {
		if($valueName === null) {
			$this->bindsCounter++;
			$valueName = ':bind'.$this->bindsCounter;
		}
		$this->boundValues[$valueName] = &$value;
		return $valueName;
	}
	
	function hasBinding(&$value){
		if(array_key_exists($value, $this->boundValues) || array_key_exists($value, $this->boundParams)){
			return true;
		}
	}
	private function prepareBindings($stmt) {
		foreach ( $this->boundValues as $key => &$val ){
			$stmt->bindValue( $key, $val );
		}
		foreach ( $this->boundParams as $key => &$val ){
			$stmt->bindParam( $key, $val );
		}
	}
	/**
	 * Returns the query expression object for query functions
	 *
	 * @return TmQueryExpression
	 */
	function expression() {
		return $this->expression;
	}
	/**
	 * Prepares the query and returns a query statement object
	 * @return PDOStatement
	 */
	function prepare() {
		$stmt = $this->db->prepare($this->getSql());
		$this->prepareBindings($stmt);
		return $stmt;
	}
}
?>