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

if(isset($_POST['submit']) && ($_POST['submit'] == 'submit')){
	
	$dblink=db_iconnect("clean");
	
	$time_start=microtime(true);

	//Recieve data from search-manu.php and store it in query
	$query=$_POST['manufacturer'];

	$sql="SELECT `equipment`.`auto_id` AS 'ID', `type`.`name` AS 'Type',`serial_num` AS 'Serial Number' FROM `equipment` JOIN `type` ON `equipment`.`type` = `type`.`auto_id` JOIN `manufacturer` ON `equipment`.`manufacturer` = `manufacturer`.`auto_id` WHERE `manufacturer`.`name` = '$query' ORDER BY `type`.`name`";

	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);

	echo '<h3>Search by manufacturer: '.$query.'</h3>';
	echo '<table width="50%" border="1">';
	echo '<tr align="center"><td>ID</td><td colspan="2">Type</td><td>Serial Number</td></tr>';

	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		echo '<tr align="center">';
		echo '<td>'.$data['ID'].'</td>';
		echo '<td colspan="2">'.$data['Type'].'</td>';
		echo '<td>'.$data['Serial Number'].'</td>';
		echo '</tr>';
	}
	echo '</table>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$exe_time=($seconds)/60;
	echo "<p>Execution Time: $exe_time minutes or $seconds seconds.</p>";
} else {
	
	$dblink=db_iconnect("clean");

	$time_start=microtime(true);

	$sql="SELECT DISTINCT(`name`) FROM `manufacturer`";

	$result=$dblink->query($sql) or 
		die("Something went wrong with $sql<br>".$dblink->error);

	echo '<form method="post" action="">'; //Send[post] data to the page itself
	echo '<select name="manufacturer">'; //Creates drop down menu with values from manufacturer

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