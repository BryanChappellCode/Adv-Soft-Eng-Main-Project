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
	
	$time_start=microtime(true);

	//Recieve data from search-manu.php and store it in query
	$query=$_POST['type'];

	$sql="SELECT `equipment`.`auto_id` AS ID, `manufacturer`.`name` AS 'Manufacturer',`serial_num` AS 'Serial Number' FROM `equipment` JOIN `manufacturer` ON `equipment`.`manufacturer` = `manufacturer`.`auto_id` JOIN `type` ON `equipment`.`type` = `type`.`auto_id` WHERE `type`.`name` = '$query' ORDER BY `manufacturer`.`name`";

	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);

	echo '<h3>Search by type: '.$query.'</h3>';
	echo '<table width="50%" border="1">';
	echo '<tr align="center"><td>ID</td><td colspan="2">Manufacturer</td><td>Serial Number</td></tr>';

	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<tr align="center">';
		echo '<td>'.$data['ID'].'</td>';
		echo '<td colspan="2">'.$data['Manufacturer'].'</td>';
		echo '<td>'.$data['Serial Number'].'</td>';
		echo '</tr>';
	}
	echo '</table>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$exe_time=($seconds)/60;
	echo "<p>Execution Time: $exe_time minutes or $seconds seconds.</p>";
} else { //If a form hasn't been recieved yet
	
	$dblink=db_iconnect("clean");

	$time_start=microtime(true);

	$sql="SELECT DISTINCT(`name`) FROM `type`";

	$result=$dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);

	echo '<form method="post" action="">'; //Send[post] data to the page itself
	echo '<select name="type">'; //Creates drop down menu with values from manufacturer

	//Populate the drop down menu
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<p>$data[0]</p>";
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}

	echo '</select>';
	//Creates a submit button, called submit, initiated as submit, display submit
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';

	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$exe_time=($seconds)/60;
	echo "<p>Execution Time: $exe_time minutes or $seconds.</p>";
}
?>