<?php

$manu=$_GET["manu"];

if(is_null($manu)){
	echo "<p>Error: No argument specified</p>";
	die("Error: No argument specified\n");
}

echo "<p>About to update Manufacturer: $manu</p>";

function db_iconnect($dbName)
{
	$un="WebUser";
	$pw="-4w@)cnQcyYMXc@Q";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
	
}

$dblink=db_iconnect("clean");

$sql="Set autocommit=0";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);

$count=0;
$time_start=microtime(true);
echo "Start Time is : $time_start\n";

//Select entire type table
$sql="SELECT * FROM `manufacturer` WHERE `name`='$manu'";

//Store entire type table into result
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);

//For each item in type table
while($item=$result->fetch_array(MYSQLI_ASSOC))
{
	//Select 1,000 entries from equipment where the type is the current  type
	$sql="SELECT * FROM `equipment_original` WHERE `manufacturer`='$item[name]'";
	
	//Store table in rslt
	$rst=$dblink->query($sql) or
		die("Somthing went wrong with: $sql<br>".$dblink->error);
	
	//For each item in rslt table
	while($data=$rst->fetch_array(MYSQLI_ASSOC))
	{
	//	echo "<p>About to update $data[auto_id] with new manufacturer:$item[name] from $data[manufacturer]</p>";
		//
		$sql="UPDATE `equipment_original` SET `manufacturer`='$item[auto_id]' WHERE `auto_id`='$data[auto_id]'";
		$dblink->query($sql) or
			die("Something went wrong with : $sql<br>".$dblink->error);	
		$count++;
	}
}

echo "<p>Committing</p>";
$sql="Commit";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);

echo "<p>Done</p>";

$time_end=microtime(true);
echo "<p>End Time is: $time_end</p>";
$seconds=$time_end-$time_start;
$execution_time=($seconds/60);
echo "<p>Execution Time: $execution_time minutes or $seconds seconds</p>";
$rowsPerSecond=($count/$seconds);
echo "<p>Rows Affected: $count</p>";
echo "<p>Rows Per Second: $rowsPerSecond per second</p>";
?>