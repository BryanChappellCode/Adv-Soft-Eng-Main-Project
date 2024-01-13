<?php

switch($_REQUEST['insertOption']){
	case 'type':
		insertType();
		break;
	case 'manufacturer':
		insertManu();
		break;
	case 'serialNumber':
		insertSN();
		break;
	default:
		$output[] = "Status: Failure";
		$output[] = "MSG: No Insert Option";
		$output[] = "Action: Resend Insert Option Data";
		$responseData = json_encode($output);
		echo $responseData;
		die();
}

function insertType(){
	
	if(!isset($_REQUEST['type'])){
		$output[] = "Status: Failure";
		$output[] = "MSG: No Type Data";
		$output[] = "Action: Resend Type Data";
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	
	$type = $_REQUEST['type'];
	$dblink = db_iconnect('clean');
	
	$time_start = microtime(true);
	
	checkExist('type', $type);
	checkSize('type', $type);
	
	$sql = 'INSERT INTO `type` (`name`) VALUES ("'.$type.'")';
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	$exetime = benchmark($time_start);
	
	if($result){
		$output[] = "Status: Success";
		$output[] = "MSG: Data Added Successfully";
		$output[] = "Action: $exetime";
		$responseData = json_encode($output);
		echo $responseData;
	}
	else{
		$output[] = "Status: Failure";
		$output[] = "MSG: Data Addition Unsuccessful";
		$output[] = "Action: Retry";
		$responseData = json_encode($output);
		echo $responseData;
	}
	
}

function insertManu(){
	
	if(!isset($_REQUEST['manufacturer'])){
		$output[] = "Status: Failure";
		$output[] = "MSG: No Manufacturer Data";
		$output[] = "Action: Resend Manufacturer Data";
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	
	$manu = $_REQUEST['manufacturer'];
	$dblink = db_iconnect('clean');
	
	$time_start = microtime(true);
	
	checkExist('manu', $manu);
	checkSize('manu', $manu);
	
	$sql = 'INSERT INTO `manufacturer` (`name`) VALUES ("'.$manu.'")';
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	$exetime = benchmark($time_start);
	
	if($result){
		$output[] = "Status: Success";
		$output[] = "MSG: Data Added Successfully";
		$output[] = "Action: $exetime";
		$responseData = json_encode($output);
		echo $responseData;
	}
	else{
		$output[] = "Status: Failure";
		$output[] = "MSG: Data Addition Unsuccessful";
		$output[] = "Action: Retry";
		$responseData = json_encode($output);
		echo $responseData;
	}
	
}

function insertSN(){
	
	if(!isset($_REQUEST['type'])){
		$output[] = "Status: Failure";
		$output[] = "MSG: No Type Data";
		$output[] = "Action: Resend Type Data";
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	if(!isset($_REQUEST['manufacturer'])){
		$output[] = "Status: Failure";
		$output[] = "MSG: No Manufacturer Data";
		$output[] = "Action: Resend Manufacturer Data";
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	if(!isset($_REQUEST['serialNum'])){
		$output[] = "Status: Failure";
		$output[] = "MSG: No Serial Number Data";
		$output[] = "Action: Resend Serial Number Data";
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	
	$type = $_REQUEST['type'];
	$manu = $_REQUEST['manufacturer'];
	$sn = $_REQUEST['serialNum'];
	$dblink = db_iconnect('clean');
	
	$time_start = microtime(true);
	
	checkExist('sn', $sn);
	checkSize('sn', $sn);
	
	$sql = 'INSERT INTO `equipment` (`type`, `manufacturer`, `serial_num`) VALUES ("'.getTypeID($type).'", "'.getManuID($manu).'", "'.$sn.'")';
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	$exetime = benchmark($time_start);
	
	if($result){
		$output[] = "Status: Success";
		$output[] = "MSG: Data Added Successfully";
		$output[] = "Action: $exetime";
		$responseData = json_encode($output);
		echo $responseData;
	}
	else{
		$output[] = "Status: Failure";
		$output[] = "MSG: Data Addition Unsuccessful";
		$output[] = "Action: Retry";
		$responseData = json_encode($output);
		echo $responseData;
	}
}


function checkSize($check, $name){
	
	$die = false;
	
	switch($check){
		case 'type':
			if(strlen($name) > 32){
				$msg = '['.$name.'] too Large! max(32)';
				$die = true;
			}
			else if(strlen($name) == 0){
				$msg = 'Input Empty!';
				$die = true;
			}
			break;
		case 'manu':
			if(strlen($name) > 32){
				$msg = '['.$name.'] too Large! max(32)';
				$die = true;
			}
			else if(strlen($name) == 0){
				$msg = 'Input Empty!';
				$die = true;
			}
			break;
		case 'sn':
			if(strlen($name) > 40){
				$msg = '['.$name.'] too Large! max(40)';
				$die = true;
			}
			else if(strlen($name) == 0){
				$msg = 'Input Empty!';
				$die = true;
			}
			break;
		default:
			echo '<h2>Error in Size Switch</h2>';
	}
	
	
	if($die){
		$output[] = "Status: Failure";
		$output[] = "MSG: ".$msg;
		$output[] = "Action: Send Data of the Correct Size";
		$responseData = json_encode($output);
		die();
	}
	
}

function checkExist($check, $name){
	
	$dblink = db_iconnect('clean');
	
	switch($check){
		case 'type':
			$sql = 'SELECT `auto_id` FROM `type` WHERE `name` LIKE "'.$name.'"';
			break;
		case 'manu':
			$sql = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE "'.$name.'"';
			break;
		case 'sn':
			$sql = 'SELECT `auto_id` FROM `equipment` WHERE `serial_num` LIKE "'.$name.'"';
			break;
		default:
			$output[] = 'Status: Failure';
			$output[] = 'MSG: Error in checkExist Switch!';
			$output[] = 'Action: Retry';
			$responseData = json_encode($output);
			echo $responseData;
			die();
	}
	
	
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if(($result->num_rows) > 0){
		$output[] = 'Status: Failure';
		$output[] = 'MSG: ['.$name.'] already Exists!';
		$output[] = 'Action: Send Nonexistant Data';
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
}

function cleanSN($sn){
	
	$snArray = preg_split('/^(SN-)/', $sn);
	
	if($snArray[0] == ''){
		$sn = $snArray[1];
	}
	
	$sn = 'SN-'.$sn;
	
	return addslashes($sn);
}

function getTypeID($typeName){
	$dblink = db_iconnect('clean');
	$typeSQL = 'SELECT `auto_id` FROM `type` WHERE `name` LIKE "'.$typeName.'"';
	$typeRst = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	$typeArr = $typeRst->fetch_array(MYSQLI_NUM);
	return $typeArr[0];	
}

function getManuID($manuName){
	$dblink = db_iconnect('clean');
	$manuSQL = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE "'.$manuName.'"';
	$manuRst = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	$manuArr = $manuRst->fetch_array(MYSQLI_NUM);
	return $manuArr[0];
}

function db_iconnect($dbName)
{
	$un="WebUser";
	$pw="-4w@)cnQcyYMXc@Q";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
	
}

function benchmark($start){
	$end=microtime(true);
	$seconds=$end-$start;
	$exe_time=($seconds)/60;
	return $exe_time;
}

?>