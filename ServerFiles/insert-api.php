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
	
	//SEND TO API
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-52-14-132-11.us-east-2.compute.amazonaws.com/api/insert?insertOption=type&type=$_SESSION[name]",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_SSL_VERIFYPEER => false
	));
		
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	if($err){
		echo '<h3>cURL Error Search API #: '.$err.'</h3>';
		die();
	}
	else{
		$results = json_decode($response, true);
	}
	
	$tmp = explode(":", $results[0]);
	$status = trim($tmp[1]);
	$tmp = explode(":", $results[1]);
	$data = trim($tmp[1]);
	$tmp = explode(":", $results[2]);
	$exetime = $tmp[1];
	
	if($status == 'Failure'){
		echo '<h1>ERROR: '.$data;
		die();
	}
	
	echo '<h1>'.$data.'</h1>';
	echo '<p>Execution Time: '.$exetime.'</p>';
	
}

function insertManu(){
	
	//SEND TO API
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-52-14-132-11.us-east-2.compute.amazonaws.com/api/insert?insertOption=manufacturer&manufacturer=$_SESSION[name]",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_SSL_VERIFYPEER => false
	));
		
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	if($err){
		echo '<h3>cURL Error Insert API #: '.$err.'</h3>';
		die();
	}
	else{
		$results = json_decode($response, true);
	}
	
	$tmp = explode(":", $results[0]);
	$status = trim($tmp[1]);
		
	$tmp = explode(":", $results[1]);
	$data = trim($tmp[1]);
	$tmp = explode(":", $results[2]);
	$exetime = $tmp[1];
	
	if($status == 'Failure'){
		echo '<h1>ERROR: '.$data;
		die();
	}
	
	echo '<h1>'.$data.'</h1>';
	echo '<p>Execution Time: '.$exetime.'</p>';
	
}

function selectSNTypeManu(){
	
	$time_start=microtime(true);
	
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
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-52-14-132-11.us-east-2.compute.amazonaws.com/api/insert?insertOption=serialNumber&type=$type&manufacturer=$manu&serialNum=$_SESSION[name]",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_SSL_VERIFYPEER => false
	));
		
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	if($err){
		echo '<h3>cURL Error Insert API #: '.$err.'</h3>';
		die();
	}
	else{
		$results = json_decode($response, true);
	}
	
	$tmp = explode(":", $results[0]);
	$status = trim($tmp[1]);
		
	$tmp = explode(":", $results[1]);
	$data = trim($tmp[1]);
	$tmp = explode(":", $results[2]);
	$exetime = $tmp[1];
	
	echo '<h1>'.$data.'</h1>';
	echo '<p>Execution Time: '.$exetime.'</p>';
		
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