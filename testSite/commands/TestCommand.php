<?php
class TestCommand extends BaseCommand {
	
	function execute($request, $response) {
		if(parent::execute($request, $response)) {
			$response->setContent('TestCommand executed');
			return true;
		}
	}
}
?>