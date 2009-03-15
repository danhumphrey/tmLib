<?php
/**
 * Contains the ResultPager class
 *
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for paging database results
 * @package tmLib
 * @subpackage Database
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class ResultPager {
	/**
	 * Total number of pages
	 * @access private
	 * @var int
	 */
	private $totalPages;

	/**
	 * The row MySQL should start it's select with
	 * @access private
	 * @var int
	 */
	private $startRow;

	/**
	 * ResultPager Constructor
	 * @param int number of rows per page
	 * @param int total number of rows available
	 * @param int current page being viewed
	 * @access public
	 */
	function __construct($rowsPerPage,$numRows,$currentPage=1) {
		// Calculate the total number of pages
		$this->totalPages=ceil($numRows/$rowsPerPage);
		// Check that a valid page has been provided
		if ( $currentPage < 1 ){
		  $currentPage=1;
		}else if ( $currentPage > $this->totalPages ){
		  $currentPage=$this->totalPages;
		}
		// Calculate the row to start the select with
		$this->startRow=(($currentPage - 1) * $rowsPerPage);

	}

	/**
	 * Returns the total number of pages available
	 * @return int
	 * @access public
	 */
	function getTotalPages () {
		return $this->totalPages;
	}

	/**
	 * Returns the row to start the select with
	 * @return int
	 * @access public
	 */
	function getStartRow() {
		return $this->startRow;
	}
}
?>