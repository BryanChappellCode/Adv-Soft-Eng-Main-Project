<?php
	
//API RECIEVES TYPE, MANUFACTURER NAME OR SERIAL NUMBER AND OUTPUTS RESULTS ACCORDINGLY

switch($_REQUEST['searchOption']){
	case 'typeAndManu':
		searchTypeAndManu();
		break;
	case 'serialNum':
		searchSN();
		break;
	default:
		
}

function searchSN(){
	
	$sn = cleanSN($_REQUEST['serialNum']);
	
	$sql = 'SELECT * FROM `equipment` WHERE `serial_num` LIKE "%'.$sn.'%"'; 
	
	$time_start = microtime(true);
	
	$dblink = db_iconnect('clean');
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if(($result->num_rows) == 0){
		$exetime = benchmark($time_start);
		$output[] = 'Status: Failure';
		$output[] = 'MSG: No Results';
		$output[] = 'Action: '.$exetime;
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	
	$exetime = benchmark($time_start);
	
	$rstArray = $result->fetch_array(MYSQLI_ASSOC);
	
	$info = $rstArray['auto_id'].','.getTypeName($rstArray['type']).','.getManuName($rstArray['manufacturer']).','.$rstArray['serial_num'];
	
	$jsonInfo = json_encode($info);
	
	$output[] = "Status: Success";
	$output[] = "MSG: $jsonInfo";
	$output[] = "Action: $exetime";
	$responseData = json_encode($output);
	//echo $responseData;
	
}
function searchTypeAndManu(){
	
	checkNull();
	
	$dblink = db_iconnect('clean');
	
	$typeName = $_REQUEST['type'];
	$manuName = $_REQUEST['manufacturer'];
	
	$time_start = microtime(true);
	
	if($typeName == "All" && $manuName == "All"){
		$sql = 'SELECT * FROM `equipment` LIMIT 1000';
		
	}
	else if($typeName == "All" && !($manuName == "All")){
		$manuID = getManuID($manuName);
		$sql = 'SELECT * FROM `equipment` WHERE `manufacturer` = "'.$manuID.'" LIMIT 1000';
	}
	else if($manuName == "All" && !($typeName == "All")){
		$typeID = getTypeID($typeName);
		$sql = 'SELECT * FROM `equipment` WHERE `type` = "'.$typeID.'" LIMIT 1000';
	}
	else {
		$typeID = getTypeID($typeName);
		$manuID = getManuID($manuName);
		$sql = 'SELECT * FROM `equipment` WHERE `manufacturer` = "'.$manuID.'" AND `type` = "'.$typeID.'" LIMIT 1000';
	}

	$results = $dblink->query($sql);
	
	$typeStat = "SELECT `type`.`auto_id` FROM `type` JOIN `status` ON `type`.`name` = `status`.`name`";
	$tStatRst = $dblink->query($typeStat) or
		die("Something went wrong with $typeStat".$dblink->error);
	$manuStat = "SELECT `manufacturer`.`auto_id` FROM `manufacturer` JOIN `status` ON `manufacturer`.`name` = `status`.`name`";
	$mStatRst = $dblink->query($manuStat) or
		die("Something went wrong with $manuStat".$dblink->error);
	
	$typeStatArr = $tStatRst->fetch_array(MYSQLI_NUM);
	$manuStatArr = $mStatRst->fetch_array(MYSQLI_NUM);
	
	if(($results->num_rows) == 0){
		$exetime = benchmark($time_start);
		$output[] = 'Status: Failure';
		$output[] = 'MSG: No Results';
		$output[] = 'Action: '.$exetime;
		$responseData = json_encode($output);
		echo $responseData;
		die();
	}
	
	while($data=$results->fetch_array(MYSQLI_ASSOC)){
		$cont = false;
		
		foreach($typeStatArr as $key=>$val){
			if($val == $data['type'])
				$cont = true;
		}
		foreach($manuStatArr as $key=>$val){
			if($val == $data['manufacturer'])
				$cont = true;
		}
		
		if(checkSNStat($data['serial_num'])){
			$cont = true;
		}
		
		if($cont == true){
			continue;
		}
		

		$info[] = $data['auto_id'].','.getTypeName($data['type']).','.getManuName($data['manufacturer']).','.$data['serial_num'];
	}
	
	$exetime = benchmark($time_start);
		
	$jsonInfo = json_encode($info);
	$output[] = 'Status: Success';
	$output[] = 'MSG: '.$jsonInfo;
	$output[] = 'Action: '.$exetime;
	$responseData = json_encode($output);
	echo $responseData;
	
}

function checkSNStat($sn){
	$dblink = db_iconnect('clean');
	
	$sql = 'SELECT `auto_id` FROM `status` WHERE `name` LIKE "%'.$sn.'%"';
	$rst = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if(($rst->num_rows) == 0){
		return false;
	}
	else{
		return true;
	}
	
}

function getTypeName($typeID){
	$dblink = db_iconnect('clean');
	$typeSQL = 'SELECT `name` FROM `type` WHERE `auto_id` = "'.$typeID.'"';
	$typeRst = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	$typeArr = $typeRst->fetch_array(MYSQLI_NUM);
	return $typeArr[0];	
	
}

function getTypeID($typeName){
	$dblink = db_iconnect('clean');
	$typeSQL = 'SELECT `auto_id` FROM `type` WHERE `name` LIKE "'.$typeName.'"';
	$typeRst = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	$typeArr = $typeRst->fetch_array(MYSQLI_NUM);
	return $typeArr[0];	
}

function getManuName($manuID){
	$dblink = db_iconnect('clean');
	$manuSQL = 'SELECT `name` FROM `manufacturer` WHERE `auto_id` = "'.$manuID.'"';
	$manuRst = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	$manuArr = $manuRst->fetch_array(MYSQLI_NUM);
	return $manuArr[0];
}

function getManuID($manuName){
	$dblink = db_iconnect('clean');
	$manuSQL = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE "'.$manuName.'"';
	$manuRst = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	$manuArr = $manuRst->fetch_array(MYSQLI_NUM);
	return $manuArr[0];
}




function benchmark($start){
	$end=microtime(true);
	$seconds=$end-$start;
	$exe_time=($seconds)/60;
	return $exe_time;
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

function checkNull(){
	
		if(!isset($_REQUEST['type'])){
		$output[] = "Status: Error";
		$output[] = "MSG: Type Data Null";
		$output[] = "Action: Resend Type Data";
		$responseData = json_encode($output);
		echo $responseData;
		unset($output);
		die();
	}

	if(!isset($_REQUEST['manufacturer'])){
		$output[] = "Status: Error";
		$output[] = "MSG: Manufacturer Data Null";
		$output[] = "Action: Resend Manufacturer Data";
		$responseData = json_encode($output);
		echo $responseData;	
		unset($output);
		die();
	}
	
}

function cleanSN($sn){
	
	$snArray = preg_split('/^(SN-)/', $sn);
	
	if($snArray[0] == ''){
		$sn = $snArray[1];
	}
	
	$sn = 'SN-'.$sn;
	
	$snClean = addslashes($sn);
	
	return $snClean;
}


?>