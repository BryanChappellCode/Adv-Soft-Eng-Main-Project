<?php
	session_start();
?>
<?php

//Main Page Controller
if(isset($_POST['submit'])){
	
	home_button();
	
	if(isset($_POST['page'])){
		switch($_POST['page']){
			case 'next':
				$_SESSION['pagenum']++;
				break;
			case 'back':
				$_SESSION['pagenum']--;
				break;
			default:
				echo '<h1>Error in Page Switch</h1>';
		}
	}
	
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
		case 'all':
			all();
			break;
		default:
			echo '<h1>Something went wrong in Main Switch</h1>';
	}
}
else{
	
	$_SESSION['pagenum'] = 0;
	$_SESSION['limit'] = 1000;
	
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
	echo '<option value="all">All</option>';
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

function filter(){
	
	$type = $_SESSION['type'];
	$manu = $_SESSION['manu'];
	
	if($type == 'All' && $manu == 'All'){
		all();
		return;
	}
	
	$pagenum = $_SESSION['pagenum'];
	$limit = $_SESSION['limit'];
	$offset = $pagenum * $limit;
	
	echo '<h1>Filter '.$manu.' by Type: '.$type.'</h1>';
	echo '<hr><br>';
	echo '<h2>Page '.($pagenum+1).'</h2>';
	
	$dblink = db_iconnect("clean");
	$time_start = microtime(true);
	
	if($type == 'All'){
		typeAll($manu);
		return;
	} else{
		$typeSQL = 'SELECT `auto_id` FROM `type` WHERE `name` = "'.$type.'"';
	}
	
	if($manu == 'All'){
		manuAll($type);
		return;
	} else{
		$manuSQL = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` = "'.$manu.'"';
	}
	
	$manuResult = $dblink->query($manuSQL) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	$typeResult = $dblink->query($typeSQL) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	$manuName = $manuResult->fetch_array(MYSQLI_NUM);
	$typeName = $typeResult->fetch_array(MYSQLI_NUM);
	
	$sql = 'SELECT `auto_id`, `serial_num` FROM `equipment` WHERE `type` = "'.$typeName[0].'" AND `manufacturer` = "'.$manuName[0].'" LIMIT '.$limit.' OFFSET '.$offset;
	
	$result = $dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<table width="50%" border="1">';
	echo '<tr align="center"><td>ID</td><td>Serial Number</td></tr>';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<tr align="center">';
		echo '<td>'.$data['auto_id'].'</td>';
		echo '<td>'.$data['serial_num'].'</td>';
		echo '</tr>';
	}
	
	echo '</table>';	
	
	$maximum = $result->num_rows;
	
	page_buttons('filter', $maximum , $offset);
	
	benchmark($time_start);
}

function typeAll($manu){
	
	$limit = $_SESSION['limit'];
	$pagenum = $_SESSION['pagenum'];
	$offset = $limit * $pagenum;
	
	$time_start = microtime(true);
	
	if(checkStatus("", $manu, "")){
		echo "<h1>Manufacturer Inactive</h1>";
		die();
	}
	
	$dblink = db_iconnect('clean');
	
	$manuSQL = 'SELECT `auto_id` FROM `manufacturer` WHERE `name` = "'.$manu.'"';
	
	$manuResult = $dblink->query($manuSQL) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	$manuName = $manuResult->fetch_array(MYSQLI_NUM);
	
	$sql = "SELECT `equipment`.`auto_id` AS 'auto_id', `type`.`name` AS 'type', `equipment`.`serial_num` AS 'serial_num' FROM `equipment` JOIN `type` ON `type`.`auto_id` = `equipment`.`type` WHERE `equipment`.`manufacturer` = $manuName[0] LIMIT $limit OFFSET $offset";
	
	$result = $dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<table width="50%" border="1">';
	echo '<tr align="center"><td>ID</td><td>Type</td><td>Serial Number</td></tr>';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<tr align="center">';
		echo '<td>'.$data['auto_id'].'</td>';
		echo '<td>'.$data['type'].'</td>';
		echo '<td>'.$data['serial_num'].'</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	
	$max = $result->num_rows;
	
	page_buttons('filter', $max, $offset);

	benchmark($time_start);
}

function manuAll($type){

	$limit = $_SESSION['limit'];
	$pagenum = $_SESSION['pagenum'];
	$offset = $limit * $pagenum;
	
	$time_start = microtime(true);
	
	if(checkStatus($type, "", "")){
		echo "<h1>Type Inactive</h1>";
		die();
	}
	
	$dblink = db_iconnect('clean');
	
	$typeSQL = 'SELECT `auto_id` FROM `type` WHERE `name` = "'.$type.'"';
	
	$typeResult = $dblink->query($typeSQL) or 
		die("Something went wrong with $typeSQL<br>".$dblink->error);
	
	$typeName = $typeResult->fetch_array(MYSQLI_NUM);
	
	$sql = "SELECT `equipment`.`auto_id` AS 'auto_id', `manufacturer`.`name` AS 'manu', `equipment`.`serial_num` AS 'serial_num' FROM `equipment` JOIN `manufacturer` ON `manufacturer`.`auto_id` = `equipment`.`manufacturer` WHERE `equipment`.`type` = $typeName[0] LIMIT $limit OFFSET $offset";
	
	$result = $dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<table width="50%" border="1">';
	echo '<tr align="center"><td>ID</td><td>Manufacturer</td><td>Serial Number</td></tr>';
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<tr align="center">';
		echo '<td>'.$data['auto_id'].'</td>';
		echo '<td>'.$data['manu'].'</td>';
		echo '<td>'.$data['serial_num'].'</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	
	$max = $result->num_rows;
	
	page_buttons('filter', $max, $offset);

	benchmark($time_start);
	
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
	
	$time_start = microtime(true);
	
	if(checkStatus("", "", $serialNum)){
		echo "<h1>Serial Number Inactive</h1>";
		die();
	}
	
	$dblink = db_iconnect('clean');
	
	$sql = "SELECT `auto_id`, `type`, `manufacturer` FROM `equipment` WHERE `serial_num` LIKE '%$serialNum%'"; 
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	
	$record = $result->fetch_array(MYSQLI_ASSOC);
	
	$typeSQL = 'SELECT `name` FROM `type` WHERE `auto_id` = '.$record['type'];
	$manuSQL = 'SELECT `name` FROM `manufacturer` WHERE `auto_id` = '.$record['manufacturer'];
	
	$typeResult = $dblink->query($typeSQL) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$manuResult = $dblink->query($manuSQL) or
		die("Something went wrong with $sql<br>".$dblink->error);
	
	$type = $typeResult->fetch_array(MYSQLI_NUM);
	$manu = $manuResult->fetch_array(MYSQLI_NUM);
	
	echo '<table width="50%" border="1">';
	echo '<tr align="center">';
	echo '<td>ID</td><td>Type</td><td>Manufacturer</td>';
	echo '</tr>';
	echo '<tr align="center">';
	echo '<td>'.$record['auto_id'].'</td>';
	echo '<td>'.$type[0].'</td>';
	echo '<td>'.$manu[0].'</td>';
	echo '</tr>';
	
	echo '</table';
	
	
	benchmark($time_start);
}

function all(){
		
	$pagenum = $_SESSION['pagenum'];
	$limit = $_SESSION['limit'];
	$offset = $pagenum * $limit;
	
	echo '<h1>Showing All</h1>';
	echo '<h2>Page '.($pagenum+1).'</h2>';
	
	$dblink = db_iconnect('clean');
	$time_start = microtime(true);
	
	$sql = 'SELECT `equipment`.`auto_id`, `type`.`name` AS "type", `manufacturer`.`name` AS "manu", `serial_num` FROM `equipment` JOIN `type` ON `type`.`auto_id` = `equipment`.`type` JOIN `manufacturer` ON `manufacturer`.`auto_id` = `equipment`.`manufacturer` LIMIT '.$limit.' OFFSET '.$offset;
	
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	
	echo '<table width="50%" border="1">';
	echo '<tr align="center">';
	echo "<td>ID</td><td>Type</td><td>Manufacturer</td><td>Serial Number</td>";
	echo "</tr>";
	
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		
		if(checkStatus($data['type'], $data['manu'], $data['serial_num'])){
			continue;
		}
		
		echo '<tr align="center">';
		echo '<td>'.$data['auto_id'].'</td>';
		echo '<td>'.$data['type'].'</td>';
		echo '<td>'.$data['manu'].'</td>';
		echo '<td>'.$data['serial_num'].'</td>';
		echo '</tr>';
		
		
	}
	
	echo '</table>';
	
	$maximum = $result->num_rows;
	
	page_buttons('all', $maximum, $offset);

	benchmark($time_start);
	
}

function checkStatus($type, $manu, $sn){
	
	$exist = 0;
	
	$dblink = db_iconnect('clean');
	$statSQL = "SELECT `name` FROM `status`";
	$status = $dblink->query($statSQL) or
		die("Something went wrong with $statSQL".$dblink->error);
	
	while($data=$status->fetch_array(MYSQLI_NUM)){
		if($data[0] == $type){
			$exist = 1;
		}
		if($data[0] == $manu){
			$exist = 1;
		}
		if($data[0] == $sn){
			$exist = 1;
		}
	}
	
	return $exist;
	
}

function page_buttons($selection, $max, $offset){
	
	$pagenum = $_SESSION['pagenum'];
	$limit = $_SESSION['limit'];
	
	echo '<table><tr>';
	if($pagenum != 0){
		echo '<td>';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="'.$selection.'" multiple/>';
		echo '<input type="hidden" name="page" value="back" multiple/>';
		echo '<button type="submit" name="submit" value="back">Back</button>';
		echo '</form>';
		echo '</td>';
	}
	
	if($max == $limit){
		echo '<td>';
		echo '<form method="post" action="">';
		echo '<input type="hidden" name="selection" value="'.$selection.'"/>';
		echo '<input type="hidden" name="page" value="next" multiple/>';
		echo '<button type="submit" name="submit" value="next">Next</button>';
		echo '</form>';
		echo '</td>';
	}
	echo '</tr></table';
	
}

function home_button(){
	
	echo '<form method="post" action="search.php">';
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