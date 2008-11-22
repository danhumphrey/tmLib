<?php
if (! defined('SIMPLE_TEST')) {
        define('SIMPLE_TEST', 'C:\\Users\\Dan\\dev\\simpletest\\');
    }
    require_once(SIMPLE_TEST . 'unit_tester.php');
    require_once(SIMPLE_TEST . 'reporter.php');
    require_once(SIMPLE_TEST . 'web_tester.php');
    require_once(SIMPLE_TEST . 'mock_objects.php');
	
	require_once('show_passes.php');
	require_once('my_reporter.php');
	
    //main tmLib includes file
    require_once('../includes.php');
?>