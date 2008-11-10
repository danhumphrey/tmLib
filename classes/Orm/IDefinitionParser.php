<?php
/**
  * IDefinitionParser interface
  * 
  * @package tmLib
  * @subpackage Orm
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * IDefinitionParser provides the interface for classes that parse orm definition data sources
 * 
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 * @abstract 
 */
interface IDefinitionParser {
	/**
	 * Parse is called to parse a specific definition from a definition data source
	 *
	 * @param string $className the classname to parse the definition of
	 * @return OrmDefinition or null
	 */
	public function parse($className);
}
?>