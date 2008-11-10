<?php
if (! defined('SIMPLE_TEST')) {
	define('SIMPLE_TEST', 'C:\\web\\simpletest\\');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'web_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once('show_passes.php');

//main tmLib includes file
require_once('../includes.php');

require_once('tst_orm_class_definitions.php');

class TestOfOrm extends UnitTestCase  {
	/**
	 * @var TmPdoDb
	 */
	private $db;

	function __construct() {
		parent::UnitTestCase();
		$this->db = new TmPdoDb(ORM_DSN,PDO_USER,PDO_PW);//,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	function setUp() {
		
		/**
		 * @var TmInsertQuery
		 */
		$q = null;
		//create 2 categories
		$q = $this->db->createInsertQuery();
		$q->insertInto('t_categories')->set('name','Cat1');
		$s = $q->prepare();
		$s->execute();
		$cat1Id = $this->db->lastInsertId();
		
		$q = $this->db->createInsertQuery();
		$q->insertInto('t_categories')->set('name','Cat2');
		$s = $q->prepare();
		$s->execute();
		$cat2Id = $this->db->lastInsertId();


		//create 3 articles
		$q = $this->db->createInsertQuery();
		$q->insertInto('t_articles')->set('category',$cat1Id)->set('title','Article1');
		$s = $q->prepare();
		$s->execute();
		$art1Id = $this->db->lastInsertId();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_articles')->set('category',$cat1Id)->set('title','Article2');
		$s = $q->prepare();
		$s->execute();
		$art2Id = $this->db->lastInsertId();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_articles')->set('category',$cat2Id)->set('title','Article3');
		$s = $q->prepare();
		$s->execute();
		$art3Id = $this->db->lastInsertId();

		//create 3 comments
		$q = $this->db->createInsertQuery();
		$q->insertInto('t_comments')->set('article',$art1Id)->set('comment','Comment1');
		$s = $q->prepare();
		$s->execute();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_comments')->set('article',$art1Id)->set('comment','Comment2');
		$s = $q->prepare();
		$s->execute();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_comments')->set('article',$art2Id)->set('comment','Comment3');
		$s = $q->prepare();
		$s->execute();

		//create 3 tags
		$q = $this->db->createInsertQuery();
		$q->insertInto('t_tags')->set('name','Tag1');
		$s = $q->prepare();
		$s->execute();
		$tag1Id = $this->db->lastInsertId();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_tags')->set('name','Tag2');
		$s = $q->prepare();
		$s->execute();
		$tag2Id = $this->db->lastInsertId();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_tags')->set('name','Tag3');
		$s = $q->prepare();
		$s->execute();
		$tag3Id = $this->db->lastInsertId();

		//associate tags to articles
		$q = $this->db->createInsertQuery();
		$q->insertInto('t_tags2articles')->set('article_id',$art1Id)->set('tag_id',$tag1Id);
		$s = $q->prepare();
		$s->execute();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_tags2articles')->set('article_id',$art1Id)->set('tag_id',$tag2Id);
		$s = $q->prepare();
		$s->execute();

		$q = $this->db->createInsertQuery();
		$q->insertInto('t_tags2articles')->set('article_id',$art2Id)->set('tag_id',$tag3Id);
		$s = $q->prepare();
		$s->execute();
	}

	function tearDown() {
		$s = $this->db->prepare('TRUNCATE TABLE `t_categories`');
		$s->execute();

		$s = $this->db->prepare('TRUNCATE TABLE `t_articles`');
		$s->execute();

		$s = $this->db->prepare('TRUNCATE TABLE `t_comments`');
		$s->execute();

		$s = $this->db->prepare('TRUNCATE TABLE `t_tags`');
		$s->execute();

		$s = $this->db->prepare('TRUNCATE TABLE `t_tags2articles`');
		$s->execute();
	}


	function testLoadWithInvalidTableDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = $orm->load('InvalidTableArticle',1);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Table 'orm_test.t_xxx' doesn't exist"),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testLoadWithInvalidColumnDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = $orm->load('InvalidColumnArticle',1);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Unknown column 'xxx' in "),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testLoadWithInvalidIdReturnsFalse() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',675);
		$this->assertFalse($art,'load() should return false for invalid id');
	}

	function testLoadWithValidId() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$this->assertTrue($art instanceof Article);
		$this->assertEqual('Article1',$art->getTitle());
		$art = $orm->load('Article',2);
		$this->assertEqual('Article2',$art->getTitle());
	}
	
	function testLoadWithDiffColumnAndPropNames() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('ArticleDiffColumnAndPropNames',1);
		$this->assertTrue($art instanceof ArticleDiffColumnAndPropNames);
		$this->assertEqual('Article1',$art->getHeadline());
	
	}
	function testSaveWithInvalidTableDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new InvalidTableArticle();
			$art->setCategory(76);
			$art->setTitle('rubbishTitle');
			$orm->save($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Table 'orm_test.t_xxx' doesn't exist"),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testSaveWithInvalidColumnDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new InvalidColumnArticle();
			$art->setCategory(76);
			$art->setTitle('rubbishTitle');
			$orm->save($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Unknown column 'xxx' in "),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testSaveWithIdThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new Article();
			$art->setId(76);
			$art->setCategory(1);
			$art->setTitle('rubbishTitle');
			$orm->save($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Cannot save class 'Article' with an existing id"),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}
	function testSave() {
		$orm = new Orm($this->db,new OrmAnnotationParser());

		//ensure article doesn't exist
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='Article4'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(0,+$count);

		//create and save
		$art = new Article();
		$art->setCategory(1);
		$art->setTitle('Article4');
		$id = $orm->save($art);

		//ensure id correct
		$this->assertEqual(4,$id);

		//ensure article exists
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='Article4'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(1,+$count);
	}

	function testUpdateWithInvalidTableDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new InvalidTableArticle();
			$art->setId(1);
			$art->setCategory(1);
			$art->setTitle('changedTitle');
			$orm->update($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Table 'orm_test.t_xxx' doesn't exist"),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testUpdateWithInvalidColumnDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new InvalidColumnArticle();
			$art->setId(1);
			$art->setCategory(1);
			$art->setTitle('changedTitle');
			$orm->update($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Unknown column 'xxx' in "),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testUpdateWithNoIdThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new Article();
			$art->setCategory(1);
			$art->setTitle('changedTitle');
			$orm->update($art);
		}catch(Exception $e)
		{
			if($e->getMessage() == "Cannot update class 'Article' without an id")
			{
				$this->pass('Exception thrown as expected.');
			} else 	{
				$this->fail('Unexpected Exception message: ' .$e->getMessage());
			}
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testUpdate() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		//get title
		$stmt = $this->db->prepare("SELECT title FROM t_articles WHERE id=1");
		$stmt->execute();
		$row = $stmt->fetch();
		$title = $row['title'];
		$this->assertEqual('Article1',$title);

		//load, change and update title
		$art = $orm->load('Article',1);
		$art->setTitle('TitleChanged');
		$result = $orm->update($art);
		$this->assertTrue($result);

		//ensure article title correct
		$stmt = $this->db->prepare("SELECT title FROM t_articles WHERE id=1");
		$stmt->execute();
		$row = $stmt->fetch();
		$title = $row['title'];
		$this->assertEqual('TitleChanged',$title);
	}

	function testUpdateNoneAffected() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		//ensure article doesn't exist
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE id=67");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(0,+$count);

		//load, change and update
		$art = new Article();
		$art->setId(67);
		$art->setCategory(1);
		$art->setTitle('InvalidId67');
		$result = $orm->update($art);
		$this->assertFalse($result);

		//ensure article title correct
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title='InvalidId67'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(0,+$count);
	}

	function testSaveOrUpdate() {

		$orm = new Orm($this->db,new OrmAnnotationParser());

		//#EXISTING
		//get title of existing article
		$stmt = $this->db->prepare("SELECT title FROM t_articles WHERE id=1");
		$stmt->execute();
		$row = $stmt->fetch();
		$title = $row['title'];
		$this->assertEqual('Article1',$title);

		//load, change and update
		$art = $orm->load('Article',1);
		$art->setTitle('TitleChanged');
		$result = $orm->saveOrUpdate($art);
		$this->assertTrue($result);

		//ensure article title correct
		$stmt = $this->db->prepare("SELECT title FROM t_articles WHERE id=1");
		$stmt->execute();
		$row = $stmt->fetch();
		$title = $row['title'];
		$this->assertEqual('TitleChanged',$title);

		//#NEW
		//ensure article doesn't exist
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='SaveOrUpdate'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(0,+$count);

		//create and save
		$art = new Article();
		$art->setCategory(1);
		$art->setTitle('SaveOrUpdate');
		$result = $orm->saveOrUpdate($art);
		$this->assertEqual(4,+$result);

		//ensure article exists
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='SaveOrUpdate'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(1,+$count);
	}

	function testDeleteWithInvalidTableDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new InvalidTableArticle();
			$art->setId(1);
			$art->setCategory(1);
			$art->setTitle('rubbishTitle');
			$orm->delete($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Table 'orm_test.t_xxx' doesn't exist"),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testDeleteWithInvalidColumnDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$art = new InvalidColumnArticle();
			$art->setId(1);
			$art->setCategory(1);
			$art->setTitle('rubbishTitle');
			$orm->delete($art);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Unknown column 'xxx' in "),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testDelete() {
		$orm = new Orm($this->db,new OrmAnnotationParser());

		//ensure article doesn't exist
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='Article3'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(1,+$count);

		//delete article
		$art = new Article();
		$art->setId(3);
		$art->setCategory(1);
		$art->setTitle('Article3');
		$result = $orm->delete($art);
		$this->assertTrue($result,'Expecting delete() to return true');

		//ensure article exists
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='Article3'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(0,+$count);
	}
	
	function testDeleteWithSingleRelationNoCascade() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$cat = $orm->getRelated($art,'Category');
		$this->assertEqual($cat->getName(),'Cat1');
		$orm->delete($art);
		$tempCat = $orm->load('Category',$cat->getId());
		$this->assertTrue(is_a($tempCat,'Category'));
	}
	
	function testDeleteWithSingleRelationYesCascade() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('ArticleWithSingleCascade',1);
		$cat = $orm->getRelated($art,'Category');
		$catId = $cat->getId();
		$this->assertEqual($cat->getName(),'Cat1');
		$orm->delete($art);
		$tempCat = $orm->load('Category',$catId);
		$this->assertFalse(is_a($tempCat,'Category'));
	}
	
	function testDeleteWithListRelationNoCascade() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$comms = $orm->getRelated($art,'Comment');
		$this->assertTrue(sizeof($comms) == 2);
		$orm->delete($art);
		$comms = $orm->getRelated($art,'Comment');
		$this->assertTrue(sizeof($comms) == 2);
	}
	
	function testDeleteWithListRelationYesCascade() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('ArticleWithListCascade',1);
		$comms = $orm->getRelated($art,'Comment');
		$this->assertTrue(sizeof($comms) == 2);
		$orm->delete($art);
		$comms = $orm->getRelated($art,'Comment');
		$this->assertTrue(sizeof($comms) == 0);
	}
	
	function testDeleteWithLookupRelationNoCascade() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$tags = $orm->getRelated($art,'Tag');
		$this->assertTrue(sizeof($tags) == 2);
		$orm->delete($art);
		$tags = $orm->getRelated($art,'Tag');
		$this->assertTrue(sizeof($tags) == 2);
	}
	
	function testDeleteWithLookupRelationYesCascade() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('ArticleWithLookupCascade',1);
		$tags = $orm->getRelated($art,'Tag');
		$this->assertTrue(sizeof($tags) == 2);
		$orm->delete($art);
		$tags = $orm->getRelated($art,'Tag');
		$this->assertTrue(sizeof($tags) == 0);
	}
	
	function testDeleteNoneAffected() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		//get article count
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(3,+$count);

		//load, change and update
		$art = new Article();
		$art->setId(67);
		$art->setCategory(1);
		$art->setTitle('InvalidId67');
		$result = $orm->delete($art);
		$this->assertFalse($result,'expecting delete() to return false');

		//ensure article count unaffected
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(3,+$count);
	}

	function testDeleteByIdWithInvalidTableDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$orm->deleteById('InvalidTableArticle',1);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Table 'orm_test.t_xxx' doesn't exist"),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testDeleteByIdWithInvalidColumnDefinitionThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		try {
			$orm->deleteById('InvalidColumnArticle',3);
		}catch(Exception $e)
		{
			$this->assertTrue(strstr($e->getMessage(),"Unknown column 'xxx' in "),'Unexpected Exception Message: ' . $e->getMessage());
			return;
		}
		$this->fail('No Exception thrown.');
	}

	function testDeleteById() {
		$orm = new Orm($this->db,new OrmAnnotationParser());

		//ensure article doesn't exist
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='Article3'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(1,+$count);

		//delete article
		$art = new Article();
		$art->setId(3);
		$art->setCategory(1);
		$art->setTitle('Article3');
		$orm->deleteById('Article',$art->getId());

		//ensure article exists
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles WHERE title ='Article3'");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(0,+$count);
	}

	function testDeleteByIdNoneAffected() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		//get article count
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(3,+$count);

		//load, change and update
		$result = $orm->deleteById('Article',67);
		$this->assertFalse($result,'expecting delete() to return false');

		//ensure article count unaffected
		$stmt = $this->db->prepare("SELECT count(*) AS artcount FROM t_articles");
		$stmt->execute();
		$row = $stmt->fetch();
		$count = $row['artcount'];
		$this->assertEqual(3,+$count);
	}

	function testGetRelatedWithNoRelationThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		try {
			$cat = $orm->getRelated($art,'Yuck');
		}catch(Exception $e)
		{
			if($e->getMessage() == "Class 'Article' does not have a relation defined for 'Yuck'")
			{
				$this->pass('Exception thrown as expected.');
			} else 	{
				$this->fail('Unexpected Exception message: ' .$e->getMessage());
			}
			return;
		}
		$this->fail('No Exception thrown.');
	}
	
	function testGetRelatedSingle() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$cat = $orm->getRelated($art,'Category');
		$this->assertEqual('Cat1',$cat->getName());	
	}
	
	function testGetRelatedSingleWithNoRelationsReturnsFalse() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$art->setCategory(76);
		$cat = $orm->getRelated($art,'Category');
		$this->assertFalse($cat);
	}

	function testGetRelatedlist() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		
		$list = $orm->getRelated($art,'Comment');
		$this->assertTrue(is_array($list));
		$this->assertEqual(2, sizeof($list));
		
		$this->assertEqual($list[0]->getComment(),'Comment1');
		$this->assertEqual($list[1]->getComment(),'Comment2');
			
	}

	function testGetRelatedlistWithNoRelationsReturnsEmptyArray() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',3);
		
		$list = $orm->getRelated($art,'Comment');
		$this->assertTrue(is_array($list));
		$this->assertTrue(empty($list));	
	}
	
	function testGetRelatedLookup() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		
		$list = $orm->getRelated($art,'Tag');
		$this->assertTrue(is_array($list));
		$this->assertEqual(2, sizeof($list));
		
		$this->assertEqual($list[0]->getName(),'Tag1');
		$this->assertEqual($list[1]->getName(),'Tag2');		
	}
	function testGetRelatedLookupWithNoRelationsReturnsEmptyArray() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',3);
		$list = $orm->getRelated($art,'Tag');
		$this->assertTrue(is_array($list));
		$this->assertEqual(0, sizeof($list));
	}
	
	function testGetRelatedReverseOfSingle() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$cat = $orm->load('Category',1);
		//List relation
		$list = $orm->getRelated($cat,'Article');
		$this->assertTrue(is_array($list));
		$this->assertEqual(2, sizeof($list));
		$this->assertEqual($list[0]->getTitle(),'Article1');
		$this->assertEqual($list[1]->getTitle(),'Article2');
	}
	
	function testGetRelatedReverseOfList() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$com = $orm->load('Comment',1);	
		//Single relation
		$art = $orm->getRelated($com,'Article');
		$this->assertTrue(is_a($art,'Article'));
		$this->assertEqual($art->getTitle(),'Article1');
	}
	function testGetRelatedReverseOfLookup() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$tag = $orm->load('Tag',1);	
		//Lookup relation
		$list = $orm->getRelated($tag,'Article');
		$this->assertTrue(is_array($list));
		$this->assertEqual(1, sizeof($list));
		$this->assertEqual($list[0]->getTitle(),'Article1');		
	}
	function testAddRelatedWithNoRelationDefinedThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$dummy = new Dummy();
		try {
			$cat = $orm->addRelated($art,$dummy);
		}catch(Exception $e)
		{
			if($e->getMessage() == "Class 'Article' does not have a relation defined for 'Dummy'")
			{
				$this->pass('Exception thrown as expected.');
			} else 	{
				$this->fail('Unexpected Exception message: ' .$e->getMessage());
			}
			return;
		}
		$this->fail('No Exception thrown.');
	}
	
	function testAddRelatedSingleNewSavesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$cat = $orm->load('Category',1);
		$art = new Article();
		$art->setTitle('Add Relation Article');
		
		//ensure only 2 related articles
		$list = $orm->getRelated($cat,'Article');
		$this->assertEqual(sizeof($list),2);
		
		$result = $orm->addRelated($cat,$art);
		$this->assertTrue($result);
		
		//test saved
		$list = $orm->getRelated($cat,'Article');
		$this->assertEqual(sizeof($list),3);
		$this->assertEqual('Add Relation Article',$list[2]->getTitle());
	}
	
	function testAddRelatedSingleExistingUpdatesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$cat = $orm->load('Category',1);
		$art = $orm->load('Article',2);
		
		$this->assertEqual(1,$art->getCategory());
		
		//ensure 2 related articles in category 1
		$list = $orm->getRelated($cat,'Article');
		$this->assertEqual(sizeof($list),2);
		
		//relate article to category 2
		$cat2 = $orm->load('Category',2);
		$result = $orm->addRelated($cat2,$art);
		$this->assertTrue($result);
		
		//ensure 1 related articles in category 1
		$list = $orm->getRelated($cat,'Article');
		$this->assertEqual(sizeof($list),1);
		
		//test saved
		$artTest = $orm->load('Article',2);
		$this->assertEqual(2,$artTest->getCategory());
	}
	
	function testAddRelatedListNewSavesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$comment = new Comment();
		$comment->setComment('Add Relation Comment');
		
		$result = $orm->addRelated($art,$comment);
		$this->assertTrue($result);
		
		//test saved
		$list = $orm->getRelated($art,'Comment');
		$this->assertEqual(sizeof($list),3);
		$this->assertEqual('Add Relation Comment',$list[2]->getComment());
	}
	
	function testAddRelatedListExistingUpdatesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		$com = $orm->load('Comment',3);
		
		$this->assertEqual(2,$com->getArticle());
		
		//ensure 2 related comments for article 1
		$list = $orm->getRelated($art,'Comment');
		$this->assertEqual(sizeof($list),2);
		
		//relate comment to article
		$result = $orm->addRelated($art,$com);
		$this->assertTrue($result);
		
		//ensure 3 related comments for article 1
		$list = $orm->getRelated($art,'Comment');
		$this->assertEqual(sizeof($list),3);
		
		//test saved
		$comTest = $orm->load('Comment',3);
		$this->assertEqual(1,$comTest->getArticle());
	}
	
	function testAddRelatedLookupNewSavesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		
		//test related tags = 2
		$list = $orm->getRelated($art,'Tag');
		$this->assertEqual(2, sizeof($list));
		
		//add relation
		$tag = new Tag();
		$tag->setName('Add Relation Tag');
		
		$result = $orm->addRelated($art,$tag);
		$this->assertTrue($result);
		
		//test saved
		$list = $orm->getRelated($art,'Tag');
		$this->assertEqual(sizeof($list),3);
		$this->assertEqual('Add Relation Tag',$list[2]->getName());
	}
	
	function testAddRelatedLookupExistingUpdatesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		
		//test related tags = 2
		$list = $orm->getRelated($art,'Tag');
		$this->assertEqual(2, sizeof($list));
		
		//get and add relation
		$tag = $orm->load('Tag',3);
		$result = $orm->addRelated($art,$tag);
		$this->assertTrue($result, 'expecting addRelated() to return true');
		
		//test saved
		$list = $orm->getRelated($art,'Tag');
		$this->assertEqual(sizeof($list),3);
	}
	
	function testRemoveRelatedWithNoRelationDefinedThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$cat = $orm->load('Category',1);
		$dummy = new Dummy();
		try {
			$orm->removeRelated($cat,$dummy);
		} catch(Exception $e)
		{
			if($e->getMessage() == "Class 'Category' does not have a relation defined for 'Dummy'")
			{
				$this->pass('Exception thrown as expected.');
			} else 	{
				$this->fail('Unexpected Exception message: ' .$e->getMessage());
			}
			return;
		}
		$this->fail('No Exception thrown.');
	}
	
	function testRemoveRelatedSingleSavesLocalPropertyAsNull() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$cat = $orm->load('Category',2);
		$art = $orm->load('Article',3);
		//get relation
		$cat = $orm->getRelated($art,'Category');
		$this->assertTrue(is_a($cat,'Category'));
		//remove related cat
		$result = $orm->removeRelated($art,$cat);
		$this->assertEqual(0,+$art->getCategory());
		$cat = $orm->getRelated($art,'Category');
		$this->assertFalse($cat);
	}
	
	function testRemoveRelatedListSavesRemotePropertyAsNull() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$cat = $orm->load('Category',1);
		$art = $orm->load('Article',1);
		//get count of relations
		$list = $orm->getRelated($cat,'Article');
		$this->assertEqual(2,sizeof($list));
		//remove related article
		$result = $orm->removeRelated($cat,$art);
		$this->assertTrue($result);
		$tempArt = $orm->load('Article',1);
		
		//original object
		$this->assertEqual(0,+$art->getCategory());
		//newly loaded object
		$this->assertEqual(0,+$tempArt->getCategory());
		//get count of relations
		$list = $orm->getRelated($cat,'Article');
		$this->assertEqual(1,sizeof($list));
	}
	
	function testRemoveRelatedLookupRemovesRelation() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$art = $orm->load('Article',1);
		//get count of relations
		$list = $orm->getRelated($art,'Tag');
		$this->assertEqual(2,sizeof($list));
		$tag1 = $list[0];
		$orm->removeRelated($art,$tag1);
		//get count of relations
		$list = $orm->getRelated($art,'Tag');
		$this->assertEqual(1,sizeof($list));
	}
	
	function testCreateFindQuery() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createFindQuery('Article');
		$this->assertTrue(is_a($q, 'TmSelectQuery'));
		$sql = $q->getSql();
		$this->assertEqual($sql, 'SELECT t_articles.id as id, t_articles.category as category, t_articles.title as title FROM t_articles');
	}
	
	function testFindByQuery() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createFindQuery('Article');
		$list = $orm->findByQuery('Article',$q);
		$this->assertEqual(sizeof($list), 3);
		foreach($list as $art)
		{
			$this->assertTrue(is_a($art,'Article'));
		}
	}
	function testFindByQueryNoneFoundReturnsEmptyArray() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createFindQuery('Article');
		$q->where($q->criteria()->eq('id',99));
		$list = $orm->findByQuery('Article',$q);
		$this->assertEqual($list, array());
	}
	
	function testCreateDeleteQuery() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createDeleteQuery('Article');
		$this->assertTrue(is_a($q, 'TmDeleteQuery'));
		$sql = $q->getSql();
		$this->assertEqual($sql, 'DELETE FROM t_articles');
	}
	
	function testDeleteByQueryInvalidQueryTypeThrowsException() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createFindQuery('Article');
		try {
			$orm->deleteByQuery($q);
		}catch(Exception $e)
		{
			if($e->getMessage() == "deleteByQuery expects a paramater of type 'TmDeleteQuery'")
			{
				$this->pass('Exception thrown as expected.');
			} else 	{
				$this->fail('Unexpected Exception message: ' .$e->getMessage());
			}
			return;
		}
		$this->fail('No Exception thrown.');	
	}
	
	function testDeleteByQuery() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createDeleteQuery('Article');
		$ret = $orm->deleteByQuery($q);
		$this->assertTrue($ret);
		//try to get list of articles
		$q = $this->db->createSelectQuery();
		$q->select('COUNT(*) as acount')->from('t_articles');
		$s = $q->prepare();
		$s->execute();
		$row = $s->fetch();
		$this->assertEqual(0,+$row['acount']);
	}
	function testDeleteByQueryNoneAffectedReturnsFalse() {
		$orm = new Orm($this->db,new OrmAnnotationParser());
		$q = $orm->createDeleteQuery('Article');
		$q->where($q->criteria()->eq('id',99));
		$ret = $orm->deleteByQuery($q);
		$this->assertFalse($ret);
	}
}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfOrm');
	$test->addTestCase(new TestOfOrm());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>