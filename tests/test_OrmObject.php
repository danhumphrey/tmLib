<?php
//tests includes
require_once('test-includes.php');


require_once('tst_orm_class_definitions.php');

/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class ArticleTest extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The title
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

class TestOfOrmObject extends UnitTestCase  {
	/**
	 * @var TmDb
	 */
	private $db;

	function __construct() {
		$this->db = new TmPdoDb(ORM_DSN,PDO_USER,PDO_PW);
		parent::UnitTestCase();
	}
	function setUp() {}
	
	function tearDown() {}
	
	function testGetAndSet() {
		$art = new ArticleTest();
		$props = array('category','title');
		foreach($props as $prop) {
			$getProp = "get$prop";
			$setProp = "set$prop";
			$this->assertNull($art->$getProp());
			
			$val1 = 'some_val';
			$art->$setProp($val1);
			$this->assertEqual($val1,$art->$getProp());
			
			$val1 = 'other_val';
			$art->$setProp($val2);
			$this->assertNotEqual($val1,$art->$getProp());
			$this->assertEqual($val2,$art->$getProp());
		}
	}
	
	function testIdCanOnlyBeSetOnce() {
		$art = new ArticleTest();
		$this->assertNull($art->getId());
		$art->setId(1);
		$this->assertEqual(1,$art->getId());
		
		$art->setId(2);
		$this->assertNotEqual(2,$art->getId());
		$this->assertEqual(1,$art->getId());
	}

}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfOrmObject');
	$test->addTestCase(new TestOfOrmObject());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>