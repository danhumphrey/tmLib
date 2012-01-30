<?php
/**
  * Orm class
  *
  * @package tmLib
  * @subpackage Orm
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * Orm provides object relational mapping between a class and a database table
 *
 * @package tmLib
 * @subpackage Orm
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class Orm {
    /**
     * @var TmPdoDb
     */
    private $db;
    /**
     * @var OrmAnnotationParser
     */
    private $defParser;

    /**
     * Constructs the Orm class
     * @param TmPdoDb $db an instance of the TmPdoDb class
     * @param IDefinitionParser $defParser a definition parser class that implements IDefinitionParser
     */
    function __construct($db,$defParser) {
        $this->db = $db;
        $this->defParser = $defParser;
    }

    /**
     * Loads an object from database based on the id
     *
     * @param string $className the classname of the object to return
     * @param int $id the id of the object to load
     * @return OrmObject an object of the type $className populated with the data from the database table
     * @throws Exception when a db error occurs
     */
    function load($className, $id) {
        //get definition
        $def = $this->defParser->parse($className);
        //get columns from def
        $columns = $def->getColumns();
        //get each property name and column definition
        foreach($columns as $name => $column) {
            if($column['type'] == 'id') {
                $idColumn = $column['name'];
                break;
            }
        }
        //build select query
        $q = $this->db->createSelectQuery();
        $q->select('*')->from($def->getTable())->where($q->criteria()->eq($idColumn,$this->db->quote($id)))->limit(1);
        $s = $q->prepare();
        $s->execute();
        $this->validateStatement($s);

        $ret = $this->createOrmObject($className,$s,$def);
        $classObject = $ret[0];
        return $classObject;
    }

    /**
     * Creates an OrmObject from a recordset
     *
     * @param string $className the classname of the object you wish to create
     * @param PDOStatement $s a statement to populate the object from
     * @param OrmDefinition $def (optional) a definition object for the class
     * @see Orm::findByQuery
     * @return OrmObject returns an OrmObject created from the recordset
     */
    private function createOrmObject($className,$s,$def = null) {
        //execute if not executed already
        if($s->errorCode() == '') {
            $s->execute();
            $this->validateStatement($s);
        }

        //parse definition if needed
        if($def === null) {
            $def = $this->defParser->parse($className);
        }
        //get columns from def
        $columns = $def->getColumns();
        $ret = array();
        //get each property name and column definition and set the property of the class from the statement
        while($row = $s->fetch(PDO::FETCH_ASSOC))
        {
            $obj = new $className();
            foreach($columns as $prop => $column) {
                $meth = 'set'.$prop;
                $val = $row[$column['name']];
                $obj->$meth($val);
            }
            $ret[] = $obj;
        }
        if(sizeof($ret)) {
            return $ret;
        }else{
            return false;
        }
    }

    /**
     * Creates a find query object for use with findByQuery()
     *
     * @param string $className the classname of the object(s) you wish to find
     * @see Orm::findByQuery
     * @return TmSelectQuery a select query with the table and column names already set
     */
    function createFindQuery($className) {
        $q = $this->db->createSelectQuery();
       	$q->setClassName($className);
       	
        $def = $this->defParser->parse($className);
        //get columns from def
        $columns = $def->getColumns();
        $table = $def->getTable();
        $cols = array();
        foreach($columns as $column) {
            $q->select($table.'.'.$column['name']. ' as '.$column['name']);
        }
        $q->from($table);
        return $q;
    }

    /**
     * Returns an array of OrmObject(s) by clasName that where loaded from the database with a find query
     *
     * @see Orm::createFindQuery
     * @param TmSelectQuery $q the query object
     * @param string optional $className the classname of the OrmObject(s) being found by the query
     * @return array an array of OrmObjects
     */
    function findByQuery($q, $className = null) {
        $s = $q->prepare();
        $s->execute();
        $this->validateStatement($s);
        if($ret = $this->createOrmObject($className == null ? $q->getClassName() : $className,$s)) {
            return $ret;
        }
        return array();
    }

    /**
     * Saves a newly created OrmObject to the database
     *
     * @param OrmObject $obj the object to save
     * @return mixed if successful, the id of the newly saved object. false otherwise.
     * @throws Exception on error
     */
    function save($obj) {
        $className = get_class($obj);
        if($obj->getId() != null) {
            throw new Exception("Cannot save class '$className' with an existing id");
        }
        $className = get_class($obj);
        $def = $this->defParser->parse($className);
        //get columns from def
        $columns = $def->getColumns();
        //build insert query
        $q = $this->db->createInsertQuery();
        $q->insertInto($def->getTable());
        foreach($columns as $prop => $column) {
            if($column['type'] != 'id') {
                //get value
                $getFunc = "get$prop";
                $val = $obj->$getFunc();
                switch (strtolower($column['type'])){
                	case 'int':
                		$q->set($column['name'],intval($val));
                		break;
                	case 'float':
                		$q->set($column['name'],floatval($val));
                		break;
                	default:
                		$q->set($column['name'],$val);
                }
            }
        }
        $res = $this->db->exec($q->getSql());
        if($this->db->errorCode() > 0) {
            $err = $this->db->errorInfo();
            throw new Exception($err[2]);
        }
        if($res ==1){
            +$id = $this->db->lastInsertId();
            $obj->setId($id);
            return $id;
        }
        return false;
    }

    /**
     * Updates an existing OrmObject and saves the data to the database
     *
     * @param OrmObject $obj the object to update
     * @return bool true if successful, false otherwise.
     * @throws Exception on error
     */
    function update($obj) {
        $className = get_class($obj);
        if($obj->getId() == null) {
            throw new Exception("Cannot update class '$className' without an id");
        }
        $def = $this->defParser->parse($className);
        //get columns from def
        $columns = $def->getColumns();
        //build insert query
        $q = $this->db->createUpdateQuery();
        $q->update($def->getTable());
        foreach($columns as $prop => $column) {
            if($column['type'] != 'id') {
                //get value
                $getFunc = "get$prop";
                $val = $obj->$getFunc();
            	switch (strtolower($column['type'])){
                	case 'int':
                		$q->set($column['name'],intval($val));
                		break;
                	case 'float':
                		$q->set($column['name'],floatval($val));
                		break;
                	default:
                		$q->set($column['name'],$val);
                }
            }
            else {
                $idColumn = $column['name'];
            }
        }
        $q->where($q->criteria()->eq($idColumn,$obj->getId()));

        $res = $this->db->exec($q->getSql());
        if($this->db->errorCode() > 0) {
            $err = $this->db->errorInfo();
            throw new Exception($err[2]);
        }
        if($res ==1){
            return true;
        }
    }

    /**
     * Saves or updates an OrmObject to the database
     *
     * @param OrmObject $obj the object to save or update
     * @return mixed if successful and new, the id of the newly saved object. if successful and existing, true. false otherwise.
     * @throws Exception on error
     */
    function saveOrUpdate($obj) {
        if($obj->getId() == null) {
            return $this->save($obj);
        }
        return $this->update($obj);
    }
    /**
     * Deletes an OrmObject from the database. Also deletes related objects of the relation is set to cascade.
     *
     * @param OrmObject $obj the object to delete
     * @return bool true if successful, false otherwise.
     * @throws Exception on error
     */
    function delete($obj) {
        $className = get_class($obj);
        return $this->deleteById($className,$obj->getId());
    }
    /**
     * Deletes an OrmObject from the database by classname and id. Also deletes related objects if the relation is set to cascade.
     *
     * @param string $className the name of the OrmObject class
     * @param int $id the id of the object to delete
     * @return bool true if successful, false otherwise.
     * @throws Exception on error
     */
    function deleteById($className, $id) {
        $def = $this->defParser->parse($className);

        //delete relations?
        $this->deleteRelated($def, $id);

        //get columns from def
        $columns = $def->getColumns();
        foreach($columns as $column) {
            if($column['type'] == 'id') {
                $idColumn = $column['name'];
                break;
            }
        }
        //build delete query
        $q = $this->db->createDeleteQuery();
        $q->deleteFrom($def->getTable())->where($q->criteria()->eq($idColumn,$this->db->quote($id)));
        $s = $q->prepare();
        $s->execute();
        $this->validateStatement($s);


        if($s->rowCount() == 1) {
            return true;
        }
    }
    /**
     * Creates a delete query object for use with deleteByQuery()
     *
     * @see Orm::deleteByQuery
     * @param string $className the classname of the object(s) you wish to delete
     * @return TmDeleteQuery a Delete query with the table and column names already set
     */
    function createDeleteQuery($className) {
        $q = $this->db->createDeleteQuery();
        $def = $this->defParser->parse($className);
        //get columns from def
        $table = $def->getTable();
        $q->deleteFrom($table);
        return $q;
    }
    /**
     * Deletes OrmObject(s) with a delete query
     *
     * @see Orm::createDeleteQuery
     * @param string $className the classname of the OrmObject(s) being found by the query
     * @param TmSelectQuery $q the query object
     * @throws Exception on error
     * @return bool true if successful, false otherwise.
     */
    function deleteByQuery($q) {
        if(!is_a($q,'TmDeleteQuery')) {
            throw new Exception('deleteByQuery expects a paramater of type \'TmDeleteQuery\'');
        }
        $s = $q->prepare();
        $s->execute();
        $this->validateStatement($s);
        if($s->rowCount() > 0) {
            return true;
        }
    }

    private function deleteRelated($def, $id) {
        $obj = $this->load($def->getClass(),$id);
        foreach($def->getRelations() as $relation) {
            if(array_key_exists('cascade',$relation)) {
                if($relation['cascade'] == 'true') {
                    switch($relation['type']) {
                        case 'Single':
                            $rel = $this->getRelated($obj,$relation['class']);
                            $this->delete($rel);
                            break;
                        case 'List':
                        case 'Lookup':
                            $rels = $this->getRelated($obj,$relation['class']);
                            foreach($rels as $rel) {
                                $this->delete($rel);
                            }
                            break;
                    }
                }

            }
        }
    }

    /**
     * Gets related object(s) from the database as specified by the relations in the orm definition
     *
     * @param OrmObject $obj the main OrmObject
     * @param string $relClass the name of the related class to retrieve
     * @param string $criteria (optional) optional criteria to filter the related objects
     * @return mixed an OrmObject for Single relations and an array of OrmObject(s) for List and Lookup relations
     */
    function getRelated($obj,$relClass, $criteria = null) {
        $className = get_class($obj);
        $def = $this->defParser->parse($className);
        $rels = $def->getRelations();
        $relation = null;
        foreach($rels as $rel){
            if(strtolower($rel['class']) == strtolower($relClass))  {
                $relation = $rel;
                break;
            }
        }
        if($relation === null){
            throw new Exception("Class '$className' does not have a relation defined for '$relClass'");
        }
        $relationDef = $this->defParser->parse($relClass);
        return $this->loadRelated($obj,$relation,$relationDef, $criteria);
    }

    private function loadRelated($obj,$relation,$relationDef,$criteria) {
        $q = $this->db->createSelectQuery();
        if(!is_null($criteria)){
        	$q->where($criteria);
        }

        $local = $relation['localProperty'];
        $getFunc = "get$local";
        $localVal = ($obj->$getFunc()) ? $obj->$getFunc() : 0;
        switch($relation['type']){
            case 'Single':
            case 'List':
                $q->select()->from($relationDef->getTable())->where($q->criteria()->eq($relation['remoteProperty'],$localVal));
                break;
            case 'Lookup':
                $q->select($relationDef->getTable().'.*')->from($relationDef->getTable())->from($relation['lookupTable'])->where($q->criteria()->eq($relation['lookupTable'].'.'.$relation['lookupLocal'],$localVal))->where($q->criteria()->eq($relationDef->getTable().'.'.$relation['remoteProperty'],$relation['lookupTable'].'.'.$relation['lookupRemote']));

        }
        $s = $q->prepare();
        $s->execute();
        $this->validateStatement($s);

        switch($relation['type']){
            case 'Single':
                $ret = $this->createOrmObject($relationDef->getClass(),$s);
                $related = $ret[0];
                break;
            case 'List':
            case 'Lookup':
                $related = array();
                if($ret = $this->createOrmObject($relationDef->getClass(),$s)) {
                    $related = $ret;
                }
                break;
        }
        return $related;
    }

    /**
     * Adds a related object as specified by the relations in the orm definition. The related object is saved to the database
     *
     * @param OrmObject $obj the main OrmObject which must already exist in the database
     * @param OrmObject $relatedObj the related OrmObject
     * @return bool true if successful
     */
    function addRelated($obj,$relatedObj) {
        $className = get_class($obj);
        $className2 = get_class($relatedObj);

        if($obj->getId() == null) {
            throw new Exception("Cannot add related objects to '$className' if it has not been saved");
        }

        $def = $this->defParser->parse($className);
        $rels = $def->getRelations();
        $relation = null;
        foreach($rels as $rel){
            if(strtolower($rel['class']) == strtolower($className2))    {
                $relation = $rel;
                break;
            }
        }
        if($relation === null){
            throw new Exception("Class '$className' does not have a relation defined for '$className2'");
        }
        $def2 = $this->defParser->parse($className2);
        //add relation property if single or list
        switch($relation['type']) {
            case 'Single':
            case 'List':
                $localGetFunc = 'get'.$relation['localProperty'];
                $remoteSetFunc = 'set'.$relation['remoteProperty'];
                $relatedObj->$remoteSetFunc($obj->$localGetFunc());
                //save or update
                return $this->saveOrUpdate($relatedObj);
                break;
            case 'Lookup':
                $localGetFunc = 'get'.$relation['localProperty'];
                $remoteGetFunc = 'get'.$relation['remoteProperty'];
                //save or update
                $this->saveOrUpdate($relatedObj);
                //insert relations into lookup table
                $q = $this->db->createInsertQuery();
                $q->insertInto($relation['lookupTable'])->set($relation['lookupLocal'],$obj->$localGetFunc())->set($relation['lookupRemote'],$relatedObj->$remoteGetFunc());
                $s = $q->prepare();
                $s->execute();
                $this->validateStatement($s);
                return true;
        }
        return false;
    }

    /**
     * Removes a relation between objects as specified in the orm definition and updates the database.
     *
     * @param OrmObject $obj the main OrmObject which must already exist in the database
     * @param OrmObject $relatedObj the OrmObject which must already be related to the main object
     * @return bool true if successful
     */
    function removeRelated($obj,$relatedObj) {
        $className = get_class($obj);
        $className2 = get_class($relatedObj);
        $def = $this->defParser->parse($className);
        $rels = $def->getRelations();
        $relation = null;
        foreach($rels as $rel){
            if(strtolower($rel['class']) == strtolower($className2))    {
                $relation = $rel;
                break;
            }
        }
        if($relation === null){
            throw new Exception("Class '$className' does not have a relation defined for '$className2'");
        }
        $def2 = $this->defParser->parse($className2);
        switch($relation['type']) {
            case 'Single':
                //set local property to null and save
                $setFunc = 'set'.$relation['localProperty'];
                $obj->$setFunc(null);
                $this->saveOrUpdate($obj);
                break;
            case 'List':
                //set remote property to null and save
                $setFunc = 'set'.$relation['remoteProperty'];
                $relatedObj->$setFunc(null);
                $this->saveOrUpdate($relatedObj);
                break;
            case 'Lookup':
                //remove relations from lookup table
                $q = $this->db->createDeleteQuery();
                $localFunc = 'get'. $relation['localProperty'];
                $remoteFunc = 'get'. $relation['remoteProperty'];

                $localVal = $obj->$localFunc();
                $remoteVal = $relatedObj->$remoteFunc();

                $q->deleteFrom($relation['lookupTable'])->where($q->criteria()->eq($relation['lookupLocal'],$localVal))->where($q->criteria()->eq($relation['lookupRemote'],$remoteVal));
                $s = $q->prepare();
                $s->execute();
                $this->validateStatement($s);
                break;
        }
        return true;
    }

    private function validateStatement($s, $q = null) {

        if($s->errorCode() > 0) {

            $err = $s->errorInfo();
            if(is_null($q))
            {
                $msg  = $err[2];
            }
            else {
                $msg  = $err[2] . ': ' . $q->getSql();
            }

            throw new Exception($msg);
        }
    }
}
?>