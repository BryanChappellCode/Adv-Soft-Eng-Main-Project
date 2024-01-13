<?php
	session_start();
?>
<?php

if(isset($_POST['submit']) && $_POST['submit'] == 'chosenEndpoint'){
	
	switch($_POST['selection']){
		case 'insert':
			insert();
			break;
		case 'modify':
			modify();
			break;
		default:
			echo 'Error in Insert/Modify Switch';
	}
	
}
else if(isset($_POST['submit']) && $_POST['submit'] == 'insert'){

	switch($_POST['selection']){
		case 'type':
			insertType($_POST['fieldInput']);
			break;
		case 'manu':
			insertManu($_POST['fieldInput']);
			break;
		case 'sn':
			insertSN($_POST['fieldInput']);
			break;
		case 'snTypeManu':
			insertSNData($_POST['snType'], $_POST['snManu'], $_SESSION['sn']);
			break;
		default:
			echo 'Error in Insert Switch';
	}
	
}
else if(isset($_POST['submit']) && $_POST['submit'] == 'modify'){
	
	switch($_POST['selection']){
		case 'type':
			modifyType();
			break;
		case 'manu':
			modifyManu();
			break;
		case 'sn':
			echo '<h1>Modify Serial Number</h1>';
			break;
		case 'typeOption':
			typeOption($_POST['type']);
			break;
		case 'manuOption':
			manuOption($_POST['manu']);
			break;

		default:
			echo 'Error in Modify Switch';
	}
	
}
else{
	
	if(isset($_SESSION['type'])){
		unset($_SESSION['type']);
	}
	if(isset($_SESSION['manu'])){
		unset($_SESSION['manu']);
	}
	if(isset($_SESSION['sn'])){
		unset($_SESSION['sn']);
	}
	
	echo '<h1>Insert or Modify Equipment</h1>';
	echo '<hr>';	
	echo '<form method="post" action="">';
	echo '<select name="selection">';
	echo '<option value="insert">Insert</option>';
	echo '<option value="modify">Modify</option>';
	echo '</select>';
	echo '<button type="submit" name="submit" value="chosenEndpoint">Submit</button?>';
	echo '</form>';
}

////////////////////////////////INSERT FUNCTIONS////////////////////////////////
function insert(){
	echo '<h1>Insert Equipment Type, Manufacturer, or Serial Number</h1>';
	echo '<hr>';
	echo '<h3>What is to be Added?</h3>';
	echo '<form method="post" action="">';
	echo '<select name="selection">';
	echo '<option value="type">Type</option>';
	echo '<option value="manu">Manufacturer</option>';
	echo '<option value="sn">Serial Number</option>';
	echo '</select>';
	echo '<input type="text" name="fieldInput" placeholder="Item Name..."/>';
	echo '<button type="submit" name="submit" value="insert">Submit</button>';
	echo '</form>';
}

function insertType($type){
	
	$type = strtolower($type);
	
	if($type == ''){
		echo "<h1>Type Field Empty!</h1>";
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';
		die();
	}
	else if(strlen($type) > 32){
		echo '<h1>Type Input too Long!</h1>';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';	
		die();
	}
	
	$dblink = db_iconnect('clean');
	
	$sql = "SELECT `name` FROM `typeTest` WHERE `name` LIKE '$type'";
	
	$results = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
			
	if($results->num_rows > 0){
		echo "<h1>Type [$type] already exists!</h1>";
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';
		die();
	}		
	
	$sql = "INSERT INTO `typeTest` (`name`) VALUES ('$type')";
	
	$results = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	
	if($results){
		echo '<h1>['.$type.'] has been Inserted Successfully</h1>';
	}
	else{
		echo '<h1>An Error Occured while trying to insert ['.$type.']</h1>';
	}
	
}

function insertManu($manu){
	
	$manu = strtolower($manu);
	$manu = ucfirst($manu);
	
	if($manu == ''){
		echo "<h1>Manufacturer Field Empty!</h1>";
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';
		die();
	}
	else if(strlen($manu) > 32){
		echo '<h1>Manufacturer Input too Long!</h1>';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';		
		die();
	}
	
	$dblink = db_iconnect('clean');
	
	$sql = "SELECT `name` FROM `manufacturerTest` WHERE `name` LIKE '$manu'";
	
	$results = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
			
	if($results->num_rows > 0){
		echo "<h1>Type [$manu] already exists!</h1>";
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';
		die();
	}		
	
	$sql = "INSERT INTO `manufacturerTest` (`name`) VALUES ('$manu')";
	
	$results = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	
	if($results){
		echo '<h1>['.$manu.'] has been Inserted Successfully';
	}
	else{
		echo '<h1>An Error Occured while trying to insert ['.$manu.']</h1>';
	}
	
}

function insertSN($sn){
	
	
	
	if($sn == ''){
		echo '<h1>SN Field Empty!</h1>';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';
		die();
	}
	else if(strlen($sn) > 40){
		echo '<h1>SN Input too Long!</h1>';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';		
		die();
	}
	
	$snArray = preg_split('/^(SN-)/', $sn);
	
	if($snArray[0] == ''){
		$sn = $snArray[1];
	}
	
	$sn = 'SN-'.$sn;
	$dblink = db_iconnect('clean');
	
	$snSQL = "SELECT `serial_num` FROM `equipment` WHERE `serial_num` LIKE '$sn'";
	
	$snResult = $dblink->query($snSQL) or
		die("Something went wrong with $snSQL".$dblink->error);
	
	if(($snResult->num_rows) > 0){
		echo '['.$sn.'] already Exists!';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="insert" />';
		echo '<button type="submit" name="submit" value="chosenEndpoint">Back</button>';
		echo '</form>';
		die();
	}
	
	echo '<h1>Specify Type and Manufacturer of ['.$sn.']</h1>';
	echo '<hr>';
	
	
	
	$typeSQL = 'SELECT `name` FROM `type`';
	
	$typeResult = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	
	$manuSQL = "SELECT `name` FROM `manufacturer`";
	
	$manuResult = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	
	$_SESSION['sn'] = $sn;
	
	echo '<form method="post" action="">';
	echo '<select name="snType">';
	while($data=$typeResult->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</td>';
	}
	echo '</select>';
	
	echo '<select name="snManu">';
	while($data=$manuResult->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</td>';
	}
	echo '</select>';
	
	echo '<button type="submit" name="submit" value="insert">Submit</button>';
	echo '<input type="hidden" name="selection" value="snTypeManu" />';
	echo '</form>';
	
}

function insertSNData($type, $manu, $sn){
	
	$dblink = db_iconnect('clean');
	
	$typeSQL = "SELECT `auto_id` FROM `type` WHERE `name` LIKE '$type'";
	$manuSQL = "SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE '$manu'";
	
	$typeResult = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	
	$manuResult = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	
	$typeArray = $typeResult->fetch_array(MYSQLI_NUM);
	$manuArray = $manuResult->fetch_array(MYSQLI_NUM);
	
	$sql = "INSERT INTO `equipment` (`type`,`manufacturer`,`serial_num`) VALUES ('$typeArray[0]', '$manuArray[0]', '$sn')";
	
	$results = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	
	if($results){
		echo '<h1>['.$sn.'] has been Inserted Successfully';
	}
	else{
		echo '<h1>An Error Occured while trying to insert ['.$sn.']</h1>';
	}
		
}

////////////////////////////////MODIFY FUNCTIONS////////////////////////////////
function modify(){
	echo '<h1>Modify Type, Manufacturer, or Serial Number</h1>';
	echo '<hr>';
	
	echo '<form method="post" action="">';
	echo '<select name="selection">';
	echo '<option value="type">Type</option>';
	echo '<option value="manu">Manufacturer</option>';
	echo '<option value="sn">Serial Number</option>';
	echo '</select>';
	echo '<button type="submit" name="submit" value="modify">Submit</button>';
	echo '</form>';	
}

function modifyType(){
	
	echo '<h1>Modifying Type</h1>';
	echo '<hr>';
	
	$dblink = db_iconnect('clean');
	
	$sql = "SELECT `name` FROM `type`";
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	echo '<form method="post" action="">';
	
	echo '<select name="type">';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	}
	
	echo '</select>';
	echo '<input type="hidden" name="selection" value="typeOption" />';
	echo '<button type="submit" name="submit" value="modify">Submit</button>';
	echo '</form>';
	
}

function modifyManu(){
	
	echo '<h1>Modifying Manufacturer</h1>';
	echo '<hr>';
	
	$dblink = db_iconnect('clean');
	
	$sql = "SELECT `name` FROM `manufacturer`";
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	echo '<form method="post" action="">';
	
	echo '<select name="manu">';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	}
	
	echo '</select>';
	echo '<input type="hidden" name="selection" value="manuOption" />';
	echo '<button type="submit" name="submit" value="modify">Submit</button>';
	echo '</form>';
	
}

function typeOption($type){
	
	$_SESSION['type'] = $type;
	
	echo '<form method="post" action="">';
	echo '<h1>Modify ['.$type.'] Name/Status';
	echo '<hr>';
	echo '<select name="option">';
	echo '<option value="name">Name</option>';
	echo '<option value="status">Status</option>';
	echo '</select>';
	echo '<input type="hidden" name="selection" value="modType" />';
	echo '<button type="submit" name="submit" value="modify">Submit</button>';
	echo '</form>';
	
}

function manuOption($manu){
	
	$_SESSION['manu'] = $manu;
	
	echo '<form method="post" action="">';
	echo '<h1>Modify ['.$manu.'] Name/Status';
	echo '<hr>';
	echo '<select name="option">';
	echo '<option value="name">Name</option>';
	echo '<option value="status">Status</option>';
	echo '</select>';
	echo '<input type="hidden" name="selection" value="modManu" />';
	echo '<button type="submit" name="submit" value="modify">Submit</button>';
	echo '</form>';
	
}

function modTypeName(){
	echo '<h1>Modifying ['.$_SESSION['type'].'] Name</h1>';
	echo '<hr>';
}

function modManuName(){
	echo '<h1>Modifying ['.$_SESSION['manu'].'] Name</h1>';
	echo '<hr>';
}

function modTypeStatus(){
	echo '<h1>Modifying ['.$_SESSION['type'].'] Status</h1>';
	echo '<hr>';
	
	$dblink = db_iconnect('clean');
	
	$sql = 'SELECT `name` FROM `status` WHERE `category` = "type" AND `name` LIKE "'.$_SESSION['type'].'"';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
		
	if(($result->num_rows) == 0){
		echo '<h3>['.$_SESSION['type'].'] is currently active</h3>';
		$active = true;
	}
	else{
		echo '<h3>['.$_SESSION['type'].'] is currently inactive</h3>';
		$active = false;
	}
	
	echo '<h4>Set Status:</h4>';
	echo '<form method="post" action="">';
	echo '<select name="status">';
	echo '<option value="active">Active</option>';
	echo '<option value="inactive">Inactive</option>';
	echo '</select>';
	echo '<input type="hidden" name="selection" value="status" />';
	echo '<button type="submit" name="submit" value="modify">Submit</button>';
	echo '</form>';
		
	}

function modManuStatus(){
	echo '<h1>Modifying ['.$_SESSION['manu'].'] Status</h1>';
}

function modStatus($status){
	
	switch($status){
		case 'inactive':
			if(isset($_SESSION['type'])){
				$sql = 'INSERT INTO `status` (`category`,`name`) VALUES ("type", "'.$_SESSION['type'].'")';
			}
			else if(isset($_SESSION['manu'])){
				$sql = 'INSERT INTO `status` (`category`,`name`) VALUES ("manufacturer", "'.$_SESSION['manu'].'")';
			}
			else if(isset($_SESSION['sn'])){
				$sql = 'INSERT INTO `status` (`category`,`name`) VALUES ("serial_num", "'.$_SESSION['sn'].'")';
			}
			else{
				echo '<h3>Error in Inactive Status Sessions</h3>';
			}
			
			$dblink = db_iconnect('clean');
			$result = $dblink->query($sql) or
				die("Something went wrong with $sql".$dblink->error);
		
	}
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

?>