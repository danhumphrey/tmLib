<?php
//tests includes
require_once('test-includes.php');

class Person {
	private $name;
	private $age;
}
class TestOfSelectQueryLiveWithPdo extends UnitTestCase  {
	
	/**
	 * db instance
	 * @var TmDb
	 */
	private $db;
	
	function __construct() {
		parent::UnitTestCase();
		$this->db = new TmPdoDb(PDO_DSN,PDO_USER,PDO_PW,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$this->db->exec('DROP TABLE IF EXISTS `people`;');
		$this->db->exec('CREATE TABLE `people` (
						  `id` int(11) NOT NULL auto_increment,
						  `name` varchar(100) collate latin1_general_ci NOT NULL,
						  `password` varchar(32) collate latin1_general_ci NOT NULL,
						  `dependents` tinyint(4) default NULL,
						  PRIMARY KEY  (`id`)
						);
						');
		$this->db->exec("INSERT INTO `people` VALUES (1, 'Dan', 'letmein', 1);");
		$this->db->exec("INSERT INTO `people` VALUES (2, 'Fred', 'hello', 3);");
		$this->db->exec("INSERT INTO `people` VALUES (3, 'Dan2', 'openup', 2);");
		$this->db->exec("INSERT INTO `people` VALUES (4, 'Bob', 'secret', NULL);");
		//addresses
		$this->db->exec('DROP TABLE IF EXISTS `addresses`;');
		$this->db->exec('CREATE TABLE `addresses` (
						`id` INT NOT NULL AUTO_INCREMENT,
						`person` INT NOT NULL ,
						`address` VARCHAR( 100 ) NOT NULL ,
						PRIMARY KEY  (`id`),
						INDEX ( `person` )
						)
						');

		$this->db->exec("INSERT INTO `addresses` VALUES (1, 1, 'Dans Address');");
		$this->db->exec("INSERT INTO `addresses` VALUES (2, 2, 'Freds Address');");
	}
	function __destruct() {
		//$this->db->exec('DROP TABLE IF EXISTS `people`;');
		//$this->db->exec('DROP TABLE IF EXISTS `addresses`;');
		$this->db = null;
	}
	function setUp() {
	}
	function tearDown() {
	}
	function testBasicStringUsage() {
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')->where($c->eq('name',"'Dan'"));
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct id for Dan
		$this->assertEqual((int)$stmt->fetchColumn(0), 1);
	}
	function testBindParamString() {
		$name = 'Dan';
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')->where($c->eq('name',$q->bindParam($name)));
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct id for Dan
		$this->assertEqual((int)$stmt->fetchColumn(0), 1);
		$name = 'Fred';
		$stmt->execute();
		//test correct id for Fred
		$this->assertEqual((int)$stmt->fetchColumn(0), 2);
	}
	function testMultipleBindParamString() {
		$name = 'Dan';
		$pw = 'letmein';
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')
			->where( 
				$c->lAnd( 
					$c->eq('name',$q->bindParam($name)), 
					$c->eq('password',$q->bindParam($pw)) 
				) );
				
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct id for Dan
		$this->assertEqual((int)$stmt->fetchColumn(0), 1);
		$name = 'Fred';
		$pw = 'hello';
		$stmt->execute();
		//test correct id for Fred
		$this->assertEqual((int)$stmt->fetchColumn(0), 2);
	}
	function testBindParamInt() {
		$id = 1;
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')->where($c->eq('id',$q->bindParam($id)));
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct name for id 1
		$this->assertEqual($stmt->fetchColumn(1), 'Dan');
		$id = 2;
		$stmt->execute();
		//test correct name for id 2
		$this->assertEqual($stmt->fetchColumn(1), 'Fred');
	}
	function testMultipleBindParamInt() {
		$id = 1;
		$deps = 1;
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')
			->where(
				$c->lAnd(
				$c->eq('id',$q->bindParam($id)),
				$c->eq('dependents',$q->bindParam($deps))
			) );

		$stmt = $q->prepare();
		$stmt->execute();
		//test correct Name
		$this->assertEqual($stmt->fetchColumn(1), 'Dan');
		$id = 2;
		$deps = 2;
		$stmt->execute();
		//test correct name
		$this->assertEqual((int)$stmt->fetchColumn(1), 'Fred');
	}
	function testBindValueString() {
		$name = 'Dan';
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')->where($c->eq('name',$q->bindValue($name)));
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct id for Dan
		$this->assertEqual((int)$stmt->fetchColumn(0), 1);
	}
	function testMultipleBindValueString() {
		$name = 'Fred';
		$pw = 'hello';
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')
		->where(
			$c->lAnd(
				$c->eq('name',$q->bindValue($name)),
				$c->eq('password',$q->bindValue($pw))
			) );
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct id for Fred
		$this->assertEqual((int)$stmt->fetchColumn(0), 2);
	}
	function testBindValueInt() {
		$id = 1;
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')->where($c->eq('id',$q->bindValue($id)));
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct name
		$this->assertEqual($stmt->fetchColumn(1), 'Dan');
	}
	function testMultipleBindValueInt() {
		$id = 2;
		$deps = 3;
		$q = new TmSelectQuery($this->db);
		$c = $q->criteria();
		$q->select('*')->from('people')
		->where(
		$c->lAnd(
		$c->eq('id',$q->bindValue($id)),
		$c->eq('dependents',$q->bindValue($deps))
		) );
		$stmt = $q->prepare();
		$stmt->execute();
		//test correct name
		$this->assertEqual((int)$stmt->fetchColumn(1), 'Fred');
	}
	
	function testSelectQueryBasicWhereJoin() {
		$q = new TmSelectQuery($this->db);
		$q->select('*')->from('people, addresses');
		$q->where($q->criteria()->eq('people.id','addresses.person'));
		$stmt = $q->prepare();
		$stmt->execute();
		$rows = $stmt->fetchAll();
		//row1
		$this->assertEqual($rows[0]['name'],'Dan');
		$this->assertEqual($rows[0]['address'],'Dans Address');
		//row2
		$this->assertEqual($rows[1]['name'],'Fred');
		$this->assertEqual($rows[1]['address'],'Freds Address');
	}
	
	function testSubSelectWithBindParam() {
		$q = new TmSelectQuery($this->db);
		$sub = $q->subSelect();
		$name = 'Dan';
		$sub->select('name')->from('people')->where($q->criteria()->ne('name',$q->bindParam($name,':name')));
		$q->select('*')->from('people')->where($q->criteria()->in('name',$sub->getSql()));
		$stmt = $q->prepare();
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$this->assertEqual(sizeof($rows),3);
		//should return all rows
		$name = 'Anthony';
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$this->assertEqual(sizeof($rows),4);
	}
	function testSubSelectWithBindValue() {
		$q = new TmSelectQuery($this->db);
		$sub = $q->subSelect();
		$name = 'Dan';
		$sub->select('name')->from('people')->where($q->criteria()->ne('name',$q->bindValue($name,':name')));
		$q->select('*')->from('people')->where($q->criteria()->in('name',$sub->getSql()));
		$stmt = $q->prepare();
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$this->assertEqual(sizeof($rows),3);
	}

}
//run if standalone
if(!isset($this)) {
	$test = new GroupTest('TestOfSelectQueryLiveWithPdo');
	$test->addTestCase(new TestOfSelectQueryLiveWithPdo());
	if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new HtmlReporter());
	}
}
?>