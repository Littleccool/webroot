<?php 

	function onAgencyReport($info){
	}

	function onAgencyIncomeReport($info){
	}
	function write_action($action){
		$file="/data/tmp/agent_report.txt";
		file_put_contents($file,$action . "\n",FILE_APPEND);
	}

	$post = file_get_contents("php://input");
	$action = $post["action"];
	
	
	write_action("receive data" . $post);
	$response=array();
	$response["ret"] = 0;
	echo json_encode($response);
?>
