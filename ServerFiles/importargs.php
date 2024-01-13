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

$dblink=db_iconnect("test");
echo "Hello from php process $argv[1] about to process file: $argv[2]\n";

$fp=fopen("/home/ubuntu/$argv[3]/$argv[2]", "r");
$count=0;
$time_start=microtime(true);
echo "PHP PID:$argv[1] - Start Time is : $time_start\n";
//Turn off autocommit
$sql="Set autocommit=0";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);

while (($row=fgetcsv($fp)) !== FALSE){
	$sql="Insert into `equipment` (`type`,`manufacturer`,`serial_num`) values ('$row[0]','$row[1]','$row[2]')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$count++;
}

//Commit rows
$sql="Commit";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>".$dblink->error);

$time_end=microtime(true);
echo "PHP PID:$argv[1] - End Time is: $time_end\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds/60);
echo "PHP PID:$argv[1] - Execution Time: $execution_time minutes or $seconds seconds\n";
$rowsPerSecond=($count/$seconds);
echo "PHP PID:$argv[1] - Rows Per Second: $rowsPerSecond per second\n";
fclose($fp);
	
?>