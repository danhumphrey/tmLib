<?php
/**
 * OrmObject class
 *
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * OrmObject is the base object for all classes wishing to persists with the Orm class
 *
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 * @abstract
 */
class OrmObject {

	protected $id;
	/**
	 * Sets the id
	 *
	 * @param int $id the id is immutable and should only be set once by the Orm class
	 */
	public function setId($id) {
		if(!$this->id) {
			$this->id = $id;
		}
	}

	/**
	 * Magic Call function used to get and set properties of the OrmObject
	 *
	 * @magic function
	 * @param string $name the function name
	 * @param mixed $args the arguments to pass to the function
	 * @return mixed the value of the property if a 'get' method is called or $this for method chaining if a 'set' method
	 */
	public function __call($name, $args) {
		if(preg_match('/^(get|set)(\w+)/',strtolower($name), $match) && $attribute = $this->validateAttribute($match[2])) {
			if('get' == $match[1]) {
				return $this->$attribute;
			} else {
				$this->$attribute = $args[0];
				return $this;
			}
		}
	}

	/**
	 * Validates an attribute is available as an OrmObject property
	 *
	 * @param string $name the name of the attribute
	 * @return string the lowercase name of the property
	 */
	protected function validateAttribute($name) {
		$ref = new ReflectionClass(get_class($this));
		$props = $ref->getProperties();
		foreach($props as $prop) {
			if(strtolower($name) == strtolower($prop->getName())) {
				return $prop->getName();
			}
		}
	}
	
	/**
	 * Converts an OrmObject to an array
	 *
	 * @return array the class properties as an array
	 */
	function toArray() {
		$ref = new ReflectionClass(get_class($this));
		$props = $ref->getProperties();
		$out = array();
		foreach($props as $prop) {
			$name = $prop->getName();
			$out[$name] = $this->$name;
		}
		return $out;
	}
	
}
?>