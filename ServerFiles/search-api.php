<?php
	session_start();
?>
<?php

//Main Page Controller
if(isset($_POST['submit'])){
	
	home_button();
	
	switch($_POST['selection']){
		case 'type':
			type();
			break;
		case 'manu':
			manu();
			break;
		case 'typeSelect':
			$_SESSION['type'] = $_POST['typeChosen'];
			typeSelect();
			break;
		case 'manuSelect':
			$_SESSION['manu'] = $_POST['manuChosen'];
			manuSelect();
			break;
		case 'filter':
			
			if(!isset($_SESSION['type'])){
				$_SESSION['type'] = $_POST['typeChosen'];
			}
			if(!isset($_SESSION['manu'])){
				$_SESSION['manu'] = $_POST['manuChosen'];
			}
			
			filter();
			break;
		case 'sn':
			sn();
			break;
		case 'searchSN':
			searchSN($_POST['snInput']);
			break;
		default:
			echo '<h1>Something went wrong in Main Switch</h1>';
	}
}
else{
	
	if(isset($_SESSION['type'])){
		unset($_SESSION['type']);
	}
	if(isset($_SESSION['manu'])){
		unset($_SESSION['manu']);
	}
	
	echo "<h1>Search Inventory</h2><hr><br>";
	echo "<h3>Search by:</h3>";
	echo '<form method="post" action"">';
	echo '<select name="selection">';
	echo '<option value="type">Type</option>';
	echo '<option value="manu">Manufacturer</option>';
	echo '<option value="sn">Serial Number</option>';
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
}

function type(){
	
	echo '<h1>Select the Type:</h1>';
	echo '<hr><br>';
	
	$dblink = db_iconnect("clean");
	
	$time_start = microtime(true);
	
	echo '<form method="post" action="">';
	echo '<p><b>Type:</b></p>';
	
	$sql="SELECT DISTINCT(`name`) FROM `type`";
	
	$result=$dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<select name="typeChosen">';
	
	echo '<p>All</p>';
	echo '<option value="All">All</option>';
	
	while($data=$result->fetch_array(MYSQLI_NUM)){
		echo "<p>$data[0]</p>";
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	
	//Sets the path for the switch statement
	echo '<input type="hidden" name="selection" value="typeSelect"/>';
	echo '</form>';
	
	benchmark($time_start);
}

function manu(){
	
	echo '<h1>Select the Manufacturer:</h1>';
	echo '<hr><br>';
	
	$dblink = db_iconnect("clean");
	
	$time_start = microtime(true);
	
	echo '<form method="post" action="">';
	echo '<p><b>Manufacturer:</b></p>';
	
	$sql="SELECT DISTINCT(`name`) FROM `manufacturer`";
	
	$result=$dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<select name="manuChosen">';
	
	echo '<p>All</p>';
	echo '<option value="All">All</option>';
	
	while($data=$result->fetch_array(MYSQLI_NUM)){
		echo "<p>$data[0]</p>";
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '<input type="hidden" name="selection" value="manuSelect" />';
	echo '</form>';
	
	benchmark($time_start);
}

function typeSelect(){
	
	$type = $_SESSION['type'];
	
	echo '<h1>Searching by Type: '.$type.'</h1>';
	echo '<hr><br>';
	
	//Filter by Manu
	$dblink = db_iconnect("clean");
	$time_start = microtime(true);
	
	echo '<form method="post" action="">';
	echo '<p><b>Filter by Manufacturer:</b></p>';
	
	$sql="SELECT DISTINCT(`name`) FROM `manufacturer`";
	
	$result=$dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<select name="manuChosen">';
	
	echo '<p>All</p>';
	echo '<option value="All">All</option>';
	
	while($data=$result->fetch_array(MYSQLI_NUM)){
		echo "<p>$data[0]</p>";
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '<input type="hidden" name="selection" value="filter"/>';
	echo '</form>';
	
	benchmark($time_start);
}

function manuSelect(){
	
	$manu = $_SESSION['manu'];
	
	echo '<h1>Searching by Manufacturer: '.$manu.'</h1>';
	echo '<hr><br>';
	
	//Filter by Type
	$dblink = db_iconnect("clean");
	$time_start = microtime(true);
	
	echo '<form method="post" action="">';
	echo '<p><b>Filter by Type:</b></p>';
	
	$sql="SELECT DISTINCT(`name`) FROM `type`";
	
	$result=$dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<select name="typeChosen">';
	
	echo '<p>All</p>';
	echo '<option value="All">All</option>';
	
	while($data=$result->fetch_array(MYSQLI_NUM)){
		echo "<p>$data[0]</p>";
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '<input type="hidden" name="selection" value="filter" multiple/>';
	echo '<input type="hidden" name="manu" value="'.$manu.'" />';
	echo '</form>';
	
	benchmark($time_start);
}

//SEND TO API
function filter(){
	
	$type = $_SESSION['type'];
	$manu = $_SESSION['manu'];

//CURL 
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-52-14-132-11.us-east-2.compute.amazonaws.com/api/search?searchOption=typeAndManu&type=$type&manufacturer=$manu",
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
	
	if($status == 'Success'){
		
		$tmp = explode(":", $results[1]);
		$data = json_decode($tmp[1],true);
		$tmp = explode(":", $results[2]);
		$exetime = $tmp[1];
		
	}else{
		
		$data = explode(":", $results[1]);
		$exetime = explode(":", $results[2]);
		echo '<h1>ERROR: '.$data[1].'</h1>';
		echo '<p>Execution Time'.$exetime[1].'</p>';
		die();
	}
	
	echo '<table width="50%" border="1">';
	echo '<tr><th align="center">ID</th><th align="center">Type</th><th align="center">Manufacturer</th><th align="center">Serial Number</th></tr>';
	
	foreach($data as $key=>$value){
		$tmp = explode(",", $value);
		echo '<tr>';
		echo '<td align="center">'.$tmp[0].'</td>';
		echo '<td align="center">'.$tmp[1].'</td>';
		echo '<td align="center">'.$tmp[2].'</td>';
		echo '<td align="center">'.$tmp[3].'</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	echo '<p>Execution Time: '.$exetime;
	
}


function sn(){
	echo '<h1>Search a Serial Number:</h1>';
	echo '<hr><br>';
	
	echo '<form method="post" action="">'; //Send[post] data to the page itself
	echo '<input type="text" placeholder="Search Serial Number..." name="snInput" multiple/>'; 
	
	//Creates a submit button, called submit, initiated as submit, display submit
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '<input type="hidden" name="selection" value="searchSN"/>';
	echo '</form>';
	
}

function searchSN($serialNum){
	echo '<h1>Searching by Serial Number: '.$serialNum.'</h1>';
	echo '<hr>';
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-52-14-132-11.us-east-2.compute.amazonaws.com/api/search?searchOption=serialNum&serialNum=$serialNum",
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
	
	if($status == 'Success'){
		
		$tmp = explode(":", $results[1]);
		$data = json_decode($tmp[1],true);
		$tmp = explode(":", $results[2]);
		$exetime = $tmp[1];
		
	}else{
		
		$data = explode(":", $results[1]);
		$exetime = explode(":", $results[2]);
		echo '<h1>ERROR: '.$data[1].'</h1>';
		echo '<p>Execution Time: '.$exetime[1].'</p>';
		die();
	}
	
	$rst = explode(",", $data);
	
	echo '<table width="50%" border="1">';
	echo '<tr align="center">';
	echo '<td>ID</td><td>Type</td><td>Manufacturer</td><td>Serial Number</td>';
	echo '</tr>';
	echo '<tr align="center">';
	echo '<td>'.$rst[0].'</td>';
	echo '<td>'.$rst[1].'</td>';
	echo '<td>'.$rst[2].'</td>';
	echo '<td>'.$rst[3].'</td>';
	echo '</tr>';
	
	echo '</table';
	echo '<p>Execution Time: '.$exetime;
	
}



function home_button(){
	
	echo '<form method="post" action="search-api.php">';
	echo '<button type="submit" name="home" value="home">Home</button>';
	echo '</form>';
	
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