<?php
/**
 * Contains the TmPdoDb class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for database access 
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @see PDO
 * @version 1.1
 *
 */
class TmPdoDb Extends PDO {
	
	/**
	 * Constructs the TmPdoDb class
	 *
	 * @param string $dsn the db dsn
	 * @param string $username the db username
	 * @param string $passwd the db password
	 * @param array $options (optional) array of PDO options
	 */
	function __construct($dsn,$username,$passwd,array $options = null) {
		//pdo extension avaialable?
		if (!extension_loaded('pdo_mysql')) {
			// load it manually
			$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
			if (!@dl($prefix . 'pdo_mysql.' . PHP_SHLIB_SUFFIX)) {
				throw new Exception('pdo_mysql extension is unavailable :-(');
			}
		}
		parent::__construct($dsn,$username,$passwd, $options);
	}
	
	/**
	 * Creates and returns a Select Query 
	 *
	 * @return TmSelectQuery
	 */
	function createSelectQuery() {
		return new TmSelectQuery($this);
	}
	/**
	 * Creates and returns an Insert Query
	 *
	 * @return TmInsertQuery
	 */
	function createInsertQuery() {
		return new TmInsertQuery($this);
	}
	/**
	 * Creates and returns an Update Query
	 *
	 * @return TmUpdateQuery
	 */
	function createUpdateQuery() {
		return new TmUpdateQuery($this);
	}
	/**
	 * Creates and returns a Delete Query
	 *
	 * @return TmDeleteQuery
	 */
	function createDeleteQuery() {
		return new TmDeleteQuery($this);
	}
}
?>