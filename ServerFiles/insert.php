<?php
	session_start();
?>

<?php
if(isset($_POST['submit'])){
	switch($_POST['selection']){
		case 'type':
			$_SESSION['name'] = $_POST['fieldInput'];
			insertType();
			break;
		case 'manu':
			$_SESSION['name'] = $_POST['fieldInput'];
			insertManu();
			break;
		case 'sn':
			$_SESSION['name'] = cleanSN($_POST['fieldInput']);
			selectSNTypeManu();
			break;
		case 'insertSN':
			insertSN($_POST['type'], $_POST['manu']);
			break;
		default:
			echo '<h2>Error in Main Switch</h2>';
	}
}
else{

	unsetSession();
	
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
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
}

function insertType(){
	
	$time_start=microtime(true);
	
	checkSize('type');
	checkExist('type');
	
	$dblink = db_iconnect('clean');
	
	$sql = 'INSERT INTO `type` (`name`) VALUES ("'.$_SESSION['name'].'")';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['name'].'] was inserted successfully</h1>';
	}
	else{
		echo '<h1>ERROR: ['.$_SESSION['name'].'] was not inserted successfully</h1>';
	}
	
	benchmark($time_start);
	
}

function insertManu(){
	
	$time_start=microtime(true);
	
	checkSize('manu');
	checkExist('manu');
	
	$dblink = db_iconnect('clean');
	
	$sql = 'INSERT INTO `manufacturer` (`name`) VALUES ("'.$_SESSION['name'].'")';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['name'].'] was inserted successfully</h1>';
	}
	else{
		echo '<h1>ERROR: ['.$_SESSION['name'].'] was not inserted successfully</h1>';
	}
	
	benchmark($time_start);
}

function selectSNTypeManu(){
	
	$time_start=microtime(true);
	
	checkSize('sn');
	checkExist('sn');
	
	echo '<h1>Select the Type and Manufacturer of ['.$_SESSION['name'].']';
	echo '<hr>';
	
	$dblink = db_iconnect('clean');
	
	$sql = 'SELECT `name` FROM `type`';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	echo '<form method="post" action="">';
	echo '<select name="type">';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	}
	
	echo '</select>';
	
	$sql = 'SELECT `name` FROM `manufacturer`';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	echo '<form method="post" action="">';
	echo '<select name="manu">';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	}
	
	echo '</select>';
	echo '<input type="hidden" name="selection" value="insertSN" />';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	benchmark($time_start);
	
}

function insertSN($type, $manu){
	
	$time_start=microtime(true);
	
	$dblink = db_iconnect('clean');
	
	$typeSQL = "SELECT `auto_id` FROM `type` WHERE `name` LIKE '$type'";
	$manuSQL = "SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE '$manu'";
	
	$typeRst = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	$manuRst = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	$typeArr = $typeRst->fetch_array(MYSQLI_NUM);
	$manuArr = $manuRst->fetch_array(MYSQLI_NUM);
	
	$sql = 'INSERT INTO `equipment` (`type`, `manufacturer`, `serial_num`) VALUES ("'.$typeArr[0].'", "'.$manuArr[0].'", "'.$_SESSION['name'].'")';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);

	if($result){
		echo '<h1>['.$_SESSION['name'].'] with Type['.$type.'] and Manufacturer['.$manu.'] was Inserted Successfully</h1>';
	}
	else{
		echo '<h1>ERROR: ['.$_SESSION['name'].'] with Type['.$type.'] and Manufacturer['.$manu.'] was not Inserted Successfully</h1>';
	}
	
	benchmark($time_start);
}


//////////////////////////////////HELPER FUNCTIONS////////////////////////////////////

function unsetSession(){

	if(isset($_SESSION['type'])){
		unset($_SESSION['type']);
	}
	if(isset($_SESSION['manu'])){
		unset($_SESSION['manu']);
	}
	if(isset($_SESSION['sn'])){
		unset($_SESSION['sn']);
	}
	if(isset($_SESSION['name'])){
		unset($_SESSION['name']);
	}
		
}

function checkSize($check){
	
	switch($check){
		case 'type':
			if(strlen($_SESSION['name']) > 32){
				echo '<h2>['.$_SESSION['name'].'] too Large! max(32)</h2>';
				die();
			}
			else if(strlen($_SESSION['name']) == 0){
				echo '<h2>Input Empty!</h2>';
				die();
			}
			break;
		case 'manu':
			if(strlen($_SESSION['name']) > 32){
				echo '<h2>['.$_SESSION['name'].'] too Large! max(32)</h2>';
				die();
			}
			else if(strlen($_SESSION['name']) == 0){
				echo '<h2>Input Empty!</h2>';
				die();
			}
			break;
		case 'sn':
			if(strlen($_SESSION['name']) > 40){
				echo '<h2>['.$_SESSION['name'].'] too Large! max(40)</h2>';
				die();
			}
			else if(strlen($_SESSION['name']) == 0){
				echo '<h2>Input Empty!</h2>';
				die();
			}
			break;
		default:
			echo '<h2>Error in Size Switch</h2>';
	}
	
}

function checkExist($check){
	
	$dblink = db_iconnect('clean');
	
	switch($check){
		case 'type':
			$sql = 'SELECT `auto_id` FROM `type` WHERE `name` LIKE "'.$_SESSION['name'].'"';
			break;
		case 'manu':
			$sql = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE "'.$_SESSION['name'].'"';
			break;
		case 'sn':
			$sql = 'SELECT `auto_id` FROM `equipment` WHERE `serial_num` LIKE "'.$_SESSION['name'].'"';
			break;
		default:
			echo '<h2>Error in Exist Switch</h2>';
	}
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if(($result->num_rows) > 0){
		echo '<h2>['.$_SESSION['name'].'] already Exists!</h2>';
		die();
	}
	
}

function cleanSN($sn){
	
	$snArray = preg_split('/^(SN-)/', $sn);
	
	if($snArray[0] == ''){
		$sn = $snArray[1];
	}
	
	$sn = 'SN-'.$sn;
	
	return $sn;
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
	echo "<p>Execution Time: $exe_time minutes or $seconds seconds.</p>";
}

?>