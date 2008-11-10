<?php
/**
 * Contains the OrmAnnotationParser class
 *
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 */

/**
 * Class for parsing ORM Definitions from php doc comments
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 *
 */
class OrmAnnotationParser Implements IDefinitionParser{
	private $def;
	/**
	 * Parses orm definitions from doc comments for the class named in the $className param
	 * @param string $className the classname to parse the definition of
	 * @return OrmDefinition or null
	 */
	function parse($className){
		//invalid class exception
		if(!class_exists($className)) {
			throw new Exception("Class '$className' does not exist" );
		}
		$ref = new ReflectionClass($className);
		//class annotation
		$dc =  $ref->getDocComment();
		$classAnnotations = $this->parseDocComments($dc);
		//exception if no table set
		if(!isset($classAnnotations['Table'])) {
			throw new Exception("Class '$className' does not have a valid TmOrmTable annotation" );
		}
		$columns = array();
		$props = $ref->getProperties();
		foreach($props as $prop) {
			if($propComments = $this->parseDocComments($prop->getDocComment())) {
				$col = array();
				if(!empty($propComments['Columns'][0]))
				{
					foreach($propComments['Columns'][0] as $name => $val)
					{
						$col[$name]=$val;
					}
					$columns[$prop->getName()] = $col;
				}
			}
		}
		
		$this->validateColumns($className,$columns);
		$this->validateRelations($className,$classAnnotations['Relations']);
		return $this->createDefinition($className,$classAnnotations['Table'],$columns,$classAnnotations['Relations']);
	}

	private function createDefinition($className,$table,$columns,$relations) {
		return new OrmDefinition($className,$table,$columns,$relations);
	}

	private function validateRelations($className, $relations)
	{
		foreach($relations as $relation) {
			if(!isset($relation['type'])) {
				throw new Exception("Class '$className' has a Relation definition without a 'type' property");
			}
			switch($relation['type']) {
				case 'Single':
					break;
				case 'List':
					break;
				case 'Lookup':
					break;
				default :
					throw new Exception("Class '$className' has a Relation definition with an invalid 'type' property");
			}
			if(!isset($relation['class'])) {
				throw new Exception("Class '$className' has a Relation definition without a 'class' property");
			}
			if(!isset($relation['localProperty'])) {
				throw new Exception("Class '$className' has a Relation definition without a 'localProperty' property");
			}
			if(!isset($relation['remoteProperty'])) {
				throw new Exception("Class '$className' has a Relation definition without a 'remoteProperty' property");
			}
			if($relation['type']=="Lookup") {
				if(!isset($relation['lookupTable'])) {
					throw new Exception("Class '$className' has a Relation definition without a 'lookupTable' property");
				}
				if(!isset($relation['lookupLocal'])) {
					throw new Exception("Class '$className' has a Relation definition without a 'lookupLocal' property");
				}
				if(!isset($relation['lookupRemote'])) {
					throw new Exception("Class '$className' has a Relation definition without a 'lookupRemote' property");
				}
			}
		}
	}
	private function validateColumns($className,$columns) {
		//no valid column definitions exception
		if(empty($columns))
		{
			throw new Exception("Class '$className' does not have any valid Column annotations" );
		}
		$idColumns = array();
		foreach($columns as $column)
		{
			if(!isset($column['name']))
			{
				throw new Exception("Class '$className' has a Column definition without a 'name' property");
			}
			if($column['type']=='id')
			{
				$idColumns[] = $column['name'];
			}
			if(empty($idColumns)) {
				throw new Exception("Class '$className' does not have an 'id' type column");
			}
			if(sizeof($idColumns) > 1) {
				throw new Exception("Class '$className' has more than 1 'id' type column");
			}
		}
	}
	private function parseDocComments($comment) {
		$annotations = array();
		//quick exit if no doc comments exist;
		if(!strstr($comment,'@')){return $annotations;}
		//clean the comment string
		$comment = $this->cleanComment($comment);
		//get each annotation line
		$annotations = explode('@',$comment);
		//parse the annotations and return them
		return $this->parseAnnotations($annotations);
	}

	private function parseAnnotationParams($params) {
		$splitParams = explode(',',$params);
		if(sizeof($splitParams) > 0)
		{
			$out = array();
			foreach($splitParams as $param)
			{
				$equalSplit = explode('=',$param);
				$out[$equalSplit[0]]= $equalSplit[1];
			}
		}
		return $out;
	}

	private function parseAnnotations($annotations) {
		//pull out prop => value array
		$props = array();
		$props['Columns'] = array();
		$props['Relations'] = array();

		foreach($annotations as $annotation)
		{
			//trim annotation
			$annotation = trim($annotation);
			if(!strlen($annotation))
			{
				continue;
			}
			$temp = substr($annotation,0,5);
			if(strlen($temp) < 5){
				continue;
			}
			//if orm annotation, split it into name and value
			if(substr_compare($temp,'TMORM',0,5,true) == 0)
			{
				$split1 = explode('(',$annotation);
				$split2 = explode(')',$split1[1]);
				$annotationName = strtoupper($split1[0]);
				switch($annotationName)
				{
					case 'TMORMTABLE':
						$props['Table'] = $split2[0];
						break;
					case 'TMORMRELATION':
						$props['Relations'][] = $this->parseAnnotationParams($split2[0]);
						break;
					case 'TMORMCOLUMN':
						$props['Columns'][] = $this->parseAnnotationParams($split2[0]);
						break;
				}

			}
		}
		return $props;
	}
	private function cleanComment($comment) {
		//strip unwanted chars
		$comment = str_replace("\n",'',$comment);
		$comment = str_replace("\r",'',$comment);
		$comment = str_replace('/','',$comment);
		$comment = str_replace('*','',$comment);
		return $comment;
	}
}

?>