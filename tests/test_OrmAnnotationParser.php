<?php
//tests includes
require_once('test-includes.php');


require_once('tst_orm_class_definitions.php');

class TestOfOrmAnnotationParser extends UnitTestCase  {
	function __construct() {
		parent::UnitTestCase();
	}
	function setUp() {}
	function tearDown() {}
	function testInvalidClassThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('RubbishInvalidClass');	
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'RubbishInvalidClass' does not exist")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message.');
			}
			return;
		}
		$this->fail('No Exception thrown.');
	}
	
	function testClassWithoutCommentsThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('ClassWithoutComments');	
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithoutComments' does not have a valid TmOrmTable annotation")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Expected Exception Not thrown.');
			}
		}
	}
	function testClassWithoutTableAnnotationThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('ClassWithoutTableAnnotation');	
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithoutTableAnnotation' does not have a valid TmOrmTable annotation")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Expected Exception Not thrown.');
			}
		}
	}
	
	function testClassWithoutColumnAnnotationsThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('ClassWithoutColumnAnnotations');		
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithoutColumnAnnotations' does not have any valid Column annotations")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message.');
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	
	function testClassWithoutColumnNameThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('ClassWithoutColumnName');		
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithoutColumnName' has a Column definition without a 'name' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message.');
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testClassWithoutIdColumnThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('ClassWithoutIdColumn');		
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithoutIdColumn' does not have an 'id' type column")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testClassWithMultipleIdColumnsThrowsException() {
		$parser = new OrmAnnotationParser();
		try{
			$def = $parser->parse('ClassWithMultipleIdColumns');		
		}
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithMultipleIdColumns' has more than 1 'id' type column")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testTableDefinitionParsed() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithTableAndColumns');
		$this->assertEqual($def->getTable(),'Table1','Expecting $def->getTable() to return \'Table1\' : %s');
	}
	function testColumnDefinitionsParsed() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithTableAndColumns');
		$this->assertEqual($def->getColumns(),array('id'=>array('name'=>'id','type'=>'id'),'title'=>array('name'=>'title','type'=>'string')));
	}
	function testColumnWithoutType() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithoutColumnType');
		$this->assertEqual($def->getColumns(),array('id'=>array('name'=>'id','type'=>'id'),'title'=>array('name'=>'title')));
	}
	function testRelationWithNoTypeThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationType');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationType' has a Relation definition without a 'type' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithInvalidTypeThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithInvalidRelationType');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithInvalidRelationType' has a Relation definition with an invalid 'type' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithNoClassThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationClass');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationClass' has a Relation definition without a 'class' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithNoLocalPropertyThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationLocalProperty');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationLocalProperty' has a Relation definition without a 'localProperty' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithNoRemotePropertyThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationRemoteProperty');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationRemoteProperty' has a Relation definition without a 'remoteProperty' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithNoLookupTablePropertyThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationLookupTable');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationLookupTable' has a Relation definition without a 'lookupTable' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithNoLookupLocalPropertyThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationLookupLocal');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationLookupLocal' has a Relation definition without a 'lookupLocal' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testRelationWithNoLookupRemotePropertyThrowsExcpetion() {
		$parser = new OrmAnnotationParser();
		try {
			$def = $parser->parse('ClassWithNoRelationLookupRemote');
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Class 'ClassWithNoRelationLookupRemote' has a Relation definition without a 'lookupRemote' property")
			{
				$this->pass('Exception thrown as expected.');
			} else 
			{
				$this->fail('Unexpected Exception message: ' . $e->getMessage());
			}
			return;
		}
		$this->fail('Expected Exception Not thrown.');
	}
	function testSingleRelationParsed() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithSingleRelation');
		$relation = array('type'=>'Single','class'=>'Category','localProperty'=>'category','remoteProperty'=>'id');
		$this->assertEqual($def->getRelations(),array(0=>($relation)));
	}
	function testListRelationParsed() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithListRelation');
		$relation = array('type'=>'List','class'=>'Comment','localProperty'=>'id','remoteProperty'=>'article');
		$this->assertEqual($def->getRelations(),array(0=>($relation)));
	}
	function testLookupRelationParsed() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithLookupRelation');
		$relation = array('type'=>'Lookup','class'=>'Tag','localProperty'=>'id','remoteProperty'=>'id','lookupTable'=>'Articles2Tags','lookupLocal'=>'article_id','lookupRemote'=>'tag_id');
		$this->assertEqual($def->getRelations(),array(0=>($relation)));
	}
	function testMultipleRelationsParsed() {
		$parser = new OrmAnnotationParser();
		$def = $parser->parse('ClassWithAllRelations');
		$relations = array(
							0=>array('type'=>'Single','class'=>'Category','localProperty'=>'category','remoteProperty'=>'id'),
							1=>array('type'=>'List','class'=>'Comment','localProperty'=>'id','remoteProperty'=>'article'),
							2=>array('type'=>'Lookup','class'=>'Tag','localProperty'=>'id','remoteProperty'=>'id','lookupTable'=>'Articles2Tags','lookupLocal'=>'article_id','lookupRemote'=>'tag_id'
						));
		$this->assertEqual($def->getRelations(),$relations);
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfOrmAnnotationParser');
	$test->addTestCase(new TestOfOrmAnnotationParser());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>