<?php
	session_start();
?>

<?php
	
if(isset($_POST['submit'])){
	switch($_POST['selection']){
		case 'type':
			$_SESSION['option'] = $_POST['modNameStatus'];
			modType();
			break;
		case 'manu':
			$_SESSION['option'] = $_POST['modNameStatus'];
			modManu();
			break;
		case 'sn':
			$_SESSION['option'] = $_POST['modNameStatus'];
			modSN();
			break;
		case 'modifyType':
			if($_SESSION['option'] == 'name'){
				$_SESSION['name'] = $_POST['fieldInput'];
				$_SESSION['type'] = $_POST['typeSelect'];
				modifyTypeName();
			}
			else if($_SESSION['option'] == 'status'){
				$_SESSION['status'] = $_POST['status'];
				$_SESSION['type'] = $_POST['typeSelect'];
				modifyTypeStatus();
			}
			break;
		case 'modifyManu':
			if($_SESSION['option'] == 'name'){
				$_SESSION['name'] = $_POST['fieldInput'];
				$_SESSION['manu'] = $_POST['manuSelect'];
				modifyManuName();
			}
			else if($_SESSION['option'] == 'status'){
				$_SESSION['status'] = $_POST['status'];
				$_SESSION['manu'] = $_POST['manuSelect'];
				modifyManuStatus();
			}
			break;
		case 'modifySN':
			if($_SESSION['option'] == 'name'){
				$_SESSION['name'] = $_POST['fieldInput'];
				$_SESSION['type'] = $_POST['type'];
				$_SESSION['manu'] = $_POST['manu'];
				modifySNIdentity();
			}
			else if($_SESSION['option'] == 'status'){
				$_SESSION['name'] = $_POST['fieldInput'];
				$_SESSION['status'] = $_POST['status'];
				modifySNStatus();
			}
			break;
		default:
			echo '<h2>Error in Main Modify Switch</h2>';
	}
}
else{

	unsetSession();
	
	echo '<h1>Modify Type, Manufacturer, or Device Identity/Status</h1>';
	echo '<hr>';

	echo '<form method="post" action="">';
	echo '<select name="selection">';
	echo '<option value="type">Type</option>';
	echo '<option value="manu">Manufacturer</option>';
	echo '<option value="sn">Serial Number</option>';
	echo '</select>';
	echo '<select name="modNameStatus">';
	echo '<option value="name">Identity</option>';
	echo '<option value="status">Status</option>';
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';

}

function modType(){
	
	$option = $_SESSION['option'];
	
	if($option == 'name'){
		echo '<h1>Modify Type Name</h1>';
		echo '<hr>';
	}
	else if($option == 'status'){
		echo '<h1>Modify Type Status</h1>';
		echo '<hr>';
	}
	else{
		echo '<h1>Error Modifying Type</h1>';
		echo '<hr>';
	}
	
	$time_start = microtime(true);
	
	$dblink = db_iconnect('clean');
	$sql = "SELECT `name` FROM `type`";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	echo '<form method="post" action="">';
	echo '<select name="typeSelect">';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	}
	
	echo '</select>';
	
	if($option == 'name'){
		echo '<input type="text" name="fieldInput" placeholder="Enter New Name..." multiple/>';
	}
	else if($option == 'status'){
		echo '<select name="status">';
		echo '<option value="active">Active</option>';
		echo '<option value="inactive">Inactive</option>';
		echo '</select>';
	}
	echo '<input type="hidden" name="selection" value="modifyType" />';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	benchmark($time_start);
}

function modManu(){
	
	$option = $_SESSION['option'];
	
	if($option == 'name'){
		echo '<h1>Modify Manufacturer Name</h1>';
		echo '<hr>';
	}
	else if($option == 'status'){
		echo '<h1>Modify Manufacturer Status</h1>';
		echo '<hr>';
	}
	else{
		echo '<h1>Error Modifying Manufacturer</h1>';
		echo '<hr>';
	}
	
	$time_start = microtime(true);
	
	$dblink = db_iconnect('clean');
	$sql = "SELECT `name` FROM `manufacturer`";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	echo '<form method="post" action="">';
	echo '<select name="manuSelect">';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	}
	
	echo '</select>';
	
	if($option == 'name'){
		echo '<input type="text" name="fieldInput" placeholder="Enter New Name..." multiple/>';
	}
	else if($option == 'status'){
		echo '<select name="status">';
		echo '<option value="active">Active</option>';
		echo '<option value="inactive">Inactive</option>';
		echo '</select>';
	}
	echo '<input type="hidden" name="selection" value="modifyManu" />';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	benchmark($time_start);
}

function modSN(){
	
	$option = $_SESSION['option'];
	
	if($option == 'name'){
		echo '<h1>Modify Device Identity</h1>';
		echo '<hr>';
	}
	else if($option == 'status'){
		echo '<h1>Modify Device Status</h1>';
		echo '<hr>';
	}
	else{
		echo '<h1>Error Modifying Serial Number</h1>';
		echo '<hr>';
		die();
	}
	
	echo '<form method="post" action="">';
	echo '<input type="text" name="fieldInput" placeholder="Enter Serial Number..." multiple/>';
	
	$time_start = microtime(true);
	
	if($option == 'name'){
		$dblink = db_iconnect('clean');
		$typeSQL = "SELECT `name` FROM `type`";
		$typeRst = $dblink->query($typeSQL) or
			die("Something went wrong with $typeSQL".$dblink->error);
		echo '<select name="type">';
		while($data=$typeRst->fetch_array(MYSQLI_ASSOC)){
			echo '<option value="'.$data['name'].'">'.$data['name'].'';
		}
		echo '</select>';
		
		$manuSQL = "SELECT `name` FROM `manufacturer`";
		$manuRst = $dblink->query($manuSQL) or
			die("Something went wrong with $typeSQL".$dblink->error);
		echo '<select name="manu">';
		while($data=$manuRst->fetch_array(MYSQLI_ASSOC)){
			echo '<option value="'.$data['name'].'">'.$data['name'].'';
		}
		echo '</select>';
		
	}
	else if($option == 'status'){
		echo '<select name="status">';
		echo '<option value="active">Active</option>';
		echo '<option value="inactive">Inactive</option>';
	}
	
	echo '<input type="hidden" name="selection" value="modifySN" />';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	benchmark($time_start);
	
}

function modifyTypeName(){
	
	$time_start = microtime(true);
	
	checkSize('type');
	checkExist('type');
	
	$dblink = db_iconnect('clean');
	$sql = 'UPDATE `type` SET `name` = "'.$_SESSION['name'].'" WHERE `name` LIKE "'.$_SESSION['type'].'"';
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['type'].'] Successfully Changed to ['.$_SESSION['name'].']';
	}
	else{
		echo '<h1>ERROR Changing ['.$_SESSION['type'].'] to ['.$_SESSION['name'].']';
	}
	
	benchmark($time_start);
}

function modifyManuName(){
	
	$time_start = microtime(true);
	
	checkSize('manu');
	checkExist('manu');
	
	$dblink = db_iconnect('clean');
	$sql = 'UPDATE `manufacturer` SET `name` = "'.$_SESSION['name'].'" WHERE `name` LIKE "'.$_SESSION['manu'].'"';
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['manu'].'] Successfully Changed to ['.$_SESSION['name'].']';
	}
	else{
		echo '<h1>ERROR Changing ['.$_SESSION['manu'].'] to ['.$_SESSION['name'].']';
	}
	
	benchmark($time_start);
}

function modifySNIdentity(){
	
	checkSize('sn');
	
	$time_start=microtime(true);
	
	$dblink = db_iconnect('clean');
	$sql = 'SELECT `auto_id` FROM `equipment` WHERE `serial_num` LIKE "'.$_SESSION['name'].'"';
	$result = $dblink->query($sql);
	
	if(($result->num_rows) == 0){
		echo '<h2>ERROR Serial Number does not Exist</h2>';
		die();
	}
	
	$idArr = $result->fetch_array(MYSQLI_NUM);
	$auto_id = $idArr[0];
	
	$typeSQL = 'SELECT `auto_id` FROM `type` WHERE `name` LIKE "'.$_SESSION['type'].'"';
	$manuSQL = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` LIKE "'.$_SESSION['manu'].'"';
	
	$typeRst = $dblink->query($typeSQL) or
		die("Something went wrong with $typeSQL".$dblink->error);
	$manuRst = $dblink->query($manuSQL) or
		die("Something went wrong with $manuSQL".$dblink->error);
	
	$typeArr = $typeRst->fetch_array(MYSQLI_NUM);
	$manuArr = $manuRst->fetch_array(MYSQLI_NUM);
	
	$sql = 'UPDATE `equipment` SET `type` = "'.$typeArr[0].'", `manufacturer` = "'.$manuArr[0].'" WHERE `auto_id` = "'.$auto_id.'"';
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['name'].'] Type/Manufacturer Successfully Updated to ['.$_SESSION['type'].'/'.$_SESSION['manu'].']</h1>';
	}
	else{
		echo "ERROR";
	}
	
	benchmark($time_start);
	
}

function modifyTypeStatus(){
	
	$time_start = microtime(true);
	
	if(checkStatus('type')){
		if($_SESSION['status'] == 'active'){
			$sql = 'DELETE FROM `status` WHERE `category` LIKE "type" AND `name` LIKE "'.$_SESSION['type'].'"';
		}
		else if($_SESSION['status'] == 'inactive'){
			echo '<h1>['.$_SESSION['type'].'] was already Inactive!</h1>';
		}
	}
	else{
		if($_SESSION['status'] == 'active'){
			echo '<h1>['.$_SESSION['type'].'] was already Active!</h1>';
		}
		else if($_SESSION['status'] == 'inactive'){
			$sql = 'INSERT INTO `status` (`category`, `name`) VALUES ("type", "'.$_SESSION['type'].'")';		
		}
	}
	
	$dblink = db_iconnect('clean');
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['type'].'] was Successfully set to '.$_SESSION['status'].'</h1>';
	}
	else{
		echo '<h1>An Error Occured while setting status</h1>';
	}
	
	benchmark($time_start);
	
}

function modifyManuStatus(){
	echo '<h1>Modifying ['.$_SESSION['manu'].'] Status to ['.$_SESSION['status'].']</h1>';
	echo '<hr>';	
	
	$time_start = microtime(true);
	
	//IF MANU INACTIVE TABLE
	if(checkStatus('manu')){
		if($_SESSION['status'] == 'active'){
			$sql = 'DELETE FROM `status` WHERE `category` LIKE "manufacturer" AND `name` LIKE "'.$_SESSION['manu'].'"';
		}
		else if($_SESSION['status'] == 'inactive'){
			echo '<h1>['.$_SESSION['manu'].'] was already Inactive!</h1>';
		}
	}
	else{
		if($_SESSION['status'] == 'active'){
			echo '<h1>['.$_SESSION['manu'].'] was already Active!</h1>';
		}
		else if($_SESSION['status'] == 'inactive'){
			$sql = 'INSERT INTO `status` (`category`, `name`) VALUES ("manufacturer", "'.$_SESSION['manu'].'")';		
		}
	}
	
	$dblink = db_iconnect('clean');
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['manu'].'] was Successfully set to '.$_SESSION['status'].'</h1>';
	}
	else{
		echo '<h1>An Error Occured while setting status</h1>';
	}
	
	benchmark($time_start);
}

function modifySNStatus(){
	
	checkSize('sn');
	
	$time_start = microtime(true);
	
	$dblink = db_iconnect('clean');
	$sql = 'SELECT `auto_id` FROM `equipment` WHERE `serial_num` LIKE "'.$_SESSION['name'].'"';
	$result = $dblink->query($sql);
	
	if(($result->num_rows) == 0){
		echo '<h2>ERROR Serial Number does not Exist</h2>';
		die();
	}

	
	if(checkStatus('sn')){
		if($_SESSION['status'] == 'active'){
			$sql = 'DELETE FROM `status` WHERE `category` LIKE "serial_num" AND `name` LIKE "'.$_SESSION['name'].'"';
		}
		else if($_SESSION['status'] == 'inactive'){
			echo '<h1>['.$_SESSION['name'].'] was already Inactive!</h1>';
		}
	}
	else{
		if($_SESSION['status'] == 'active'){
			echo '<h1>['.$_SESSION['name'].'] was already Active!</h1>';
		}
		else if($_SESSION['status'] == 'inactive'){
			$sql = 'INSERT INTO `status` (`category`, `name`) VALUES ("serial_num", "'.$_SESSION['name'].'")';		
		}
	}
	
	$dblink = db_iconnect('clean');
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if($result){
		echo '<h1>['.$_SESSION['name'].'] was Successfully set to '.$_SESSION['status'].'</h1>';
	}
	else{
		echo '<h1>An Error Occured while setting status</h1>';
	}
	
	benchmark($time_start);
}

///////////////////////////////HELPER FUNCTIONS///////////////////////////////////

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

function checkStatus($check){
	$dblink = db_iconnect('clean');
	
	switch($check){
		case 'type':
			$sql = 'SELECT `name` FROM `status` WHERE `category` LIKE "type" AND `name` LIKE "'.$_SESSION['type'].'"';
			break;
		case 'manu':
			$sql = 'SELECT `name` FROM `status` WHERE `category` LIKE "manufacturer" AND `name` LIKE "'.$_SESSION['manu'].'"';
			break;
		case 'sn':
			$sql = 'SELECT `name` FROM `status` WHERE `category` LIKE "serial_num" AND `name` LIKE "'.$_SESSION['name'].'"';
			break;
		default:
			echo '<h2>Error in Exist Switch</h2>';
	}
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql".$dblink->error);
	
	if(($result->num_rows) > 0){
		return 1;
	}
	else{
		return 0;
	}
}

function unsetSession(){
	
	if(isset($_SESSION['option'])){
		unset($_SESSION['option']);
	}
	if(isset($_SESSION['name'])){
		unset($_SESSION['name']);
	}
	if(isset($_SESSION['status'])){
		unset($_SESSION['status']);
	}
	if(isset($_SESSION['type'])){
		unset($_SESSION['type']);
	}
	if(isset($_SESSION['manu'])){
		unset($_SESSION['manu']);
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

function benchmark($start){
	$end=microtime(true);
	$seconds=$end-$start;
	$exe_time=($seconds)/60;
	echo "<p>Execution Time: $exe_time minutes or $seconds seconds.</p>";
}

?>