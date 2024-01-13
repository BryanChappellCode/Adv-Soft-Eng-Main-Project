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

$dblink=db_iconnect("clean");

$sql="Set autocommit=0";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);

//Select entire type table
$sql="SELECT * FROM `type` WHERE `name`='computer'";

//Store entire type table into result
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);

//For each item in type table
while($item=$result->fetch_array(MYSQLI_ASSOC))
{
	//Select 1,000 entries from equipment where the type is the current  type
	$sql="SELECT * FROM `equipment_original` WHERE `type`='$item[name]'";
	
	//Store table in rslt
	$rst=$dblink->query($sql) or
		die("Somthing went wrong with: $sql<br>".$dblink->error);

	//For each item in rslt table
	while($data=$rst->fetch_array(MYSQLI_ASSOC))
	{
		echo "<p>About to update $data[auto_id] with new type:$item[name] from $data[type]</p>";
		//
		$sql="UPDATE `equipment_original` SET `type`='$item[auto_id]' WHERE `auto_id`='$data[auto_id]'";
		$dblink->query($sql) or
			die("Something went wrong with : $sql<br>".$dblink->error);	
	}
}

$sql="Commit";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);

echo "<p>Done</p>"
?>