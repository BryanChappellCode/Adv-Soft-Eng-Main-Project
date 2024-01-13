<?php

function db_iconnect($dbName)
{
	$un="WebUser";
	$pw="-4w@)cnQcyYMXc@Q";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
	
}

//If Post has a value and is equal to submit
if(isset($_POST['submit']) && ($_POST['submit'] == 'submit')){
	
	$dblink=db_iconnect("clean");
	
//	echo $_POST['search'];
	
	$time_start=microtime(true);

	//Recieve data from search-manu.php and store it in query
	$query=$_POST['search'];

	$sql="SELECT `equipment`.`auto_id` AS 'ID', `type`.`name` AS 'Type', `manufacturer`.`name` AS 'Manufacturer' FROM `equipment` JOIN `type` ON `equipment`.`type` = `type`.`auto_id` JOIN `manufacturer` ON `equipment`.`manufacturer` = `manufacturer`.`auto_id` WHERE `equipment`.`serial_num` LIKE '%$query%'";

	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);

	echo '<h3>Search by serial number: '.$query.'</h3>';
	echo '<table width="50%" border="1">';
	echo '<tr align="center"><td>ID</td><td colspan="2">Type</td><td>Manufacturer</td></tr>';

	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<tr align="center">';
		echo '<td>'.$data['ID'].'</td>';
		echo '<td colspan="2">'.$data['Type'].'</td>';
		echo '<td>'.$data['Manufacturer'].'</td>';
		echo '</tr>';
	}
	echo '</table>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$exe_time=($seconds)/60;
	echo "<p>Execution Time: $exe_time minutes or $seconds seconds.</p>";
} else { //If a form hasn't been recieved yet

	echo '<form method="post" action="">'; //Send[post] data to the page itself
	echo '<input type="text" placeholder="Search Serial Number..." name="search">'; //Creates drop down menu with values from manufacturer
	
	echo '</input>';
	//Creates a submit button, called submit, initiated as submit, display submit
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
}
?>