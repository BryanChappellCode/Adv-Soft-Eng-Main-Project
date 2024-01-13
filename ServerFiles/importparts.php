<?php

$files=array('equip-partaa','equip-partab','equip-partac','equip-partad','equip-partae');

foreach($files as $key=>$value)
{
	shell_exec("/usr/bin/php /var/www/html/importargs.php $key $value $argv[1]> /home/ubuntu/$argv[1]/$value.log 2>/home/ubuntu/$argv[1]/$value.log &");
}

echo "Main Process Done\n";

?>