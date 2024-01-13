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

$fp=fopen("/home/ubuntu/COPY.txt", "r");
$count=0;
$time_start=microtime(true);
echo "<p> Start Time is : $time_start</p>";
while (($row=fgetcsv($fp)) !== FALSE){
	$sql="Insert into `equipment` (`type`,`manufacturer`,`serial_num`) values ('$row[0]','$row[1]','$row[2]')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$count++;
}
$time_end=microtime(true);
echo "<p> End Time is: $time_end</p>\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds/60);
echo "<p> Execution Time: $execution_time minutes or $seconds seconds</p>";
$rowsPerSecond=($count/$seconds);
echo "<p> Rows Per Second: $rowsPerSecond per second<\p>\n";
fclose($fp);
	
?>