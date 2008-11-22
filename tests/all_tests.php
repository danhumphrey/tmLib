<?php
	
	//tests includes
	require_once('test-includes.php');
    
	/**
	 * TODO:Group tests and check coverage
	 * @todo extract loading to external loader
	 */
    
    $test = new GroupTest('All tmLib tests');
    
    $test->addTestFile('test_Auth.php');
    $test->addTestFile('test_AuthDefinition.php');
    $test->addTestFile('test_AutoLoad.php');
    
    $test->addTestFile('test_BaseCommand.php');
    
    $test->addTestFile('test_CommandMapper.php');
    $test->addTestFile('test_DataSet.php');
    $test->addTestFile('test_Filters.php');
    $test->addTestFile('test_FrontController.php');
    $test->addTestFile('test_HttpPathVars.php');
    $test->addTestFile('test_HttpRequest.php');
    $test->addTestFile('test_HttpResponse.php');
    
    $test->addTestFile('test_InputField.php');
    $test->addTestFile('test_InputProcessor.php');
    $test->addTestFile('test_PageMapper.php');
    
    $test->addTestFile('test_Registry.php');
    $test->addTestFile('test_ResultPager.php');
    $test->addTestFile('test_Rules.php');
    $test->addTestFile('test_ServerPage.php');
    $test->addTestFile('test_Session.php');
    $test->addTestFile('test_View.php');
    
    //*
    $test->addTestFile('test_DeleteQuery.php');
   	$test->addTestFile('test_InsertQuery.php');
    $test->addTestFile('test_PdoDb.php');
   	$test->addTestFile('test_Query.php');
   	$test->addTestFile('test_QueryCriteria.php');
   	$test->addTestFile('test_QueryExpression.php');
   	$test->addTestFile('test_SelectQuery.php');
    $test->addTestFile('test_SelectQueryLiveWithPdo.php');
   	$test->addTestFile('test_SubSelectQuery.php'); 
    $test->addTestFile('test_UpdateQuery.php');
    $test->addTestFile('test_WhereQuery.php');
   
    $test->addTestFile('../tests/test_Orm.php');
   	$test->addTestFile('../tests/test_OrmAnnotationParser.php');
   	$test->addTestFile('../tests/test_OrmDefinition.php');
   	$test->addTestFile('../tests/test_OrmObject.php');
   	// */	
    //run
    if(isset($_GET['showpasses']))
	{
		$test->run(new ShowPasses());
	} else {
		$test->run(new MyReporter());
	}

?>