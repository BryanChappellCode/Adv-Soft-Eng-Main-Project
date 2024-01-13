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
$sql="SELECT count(`manufacturer`) FROM `equipment_original` where `manufacturer`='Microsoft'";
$time_start=microtime(true);
$result=$dblink->query($sql) or	
	die("Something went wrong with: $sql<br>".$dblink->error);
$count=$result->fetch_array(MYSQLI_NUM);
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$exe_time=($seconds)/60;
echo "<p>Number of rows for manufacturer type: Microsoft = $count[0].</p>";
echo "<p>Execution Time: $exe_time minutes or $seconds seconds.</p>";

?>