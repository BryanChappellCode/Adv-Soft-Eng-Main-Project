<?php


header('Content-Type: application/json');
header('HTTP/1.1 200 OK');
/*
//API Data & Response goes here
$output[] = 'Status: API Main';
$output[] = 'MSG: Primary Endpoint Reached';
$output[] = 'Action: None';

$responseData = json_encode($output);

echo $responseData;
*/

$url = $_SERVER['REQUEST_URI']; //Request URI component of URL

//echo '<h3>'.$url.'</h3>';

$path = parse_url($url, PHP_URL_PATH);

$pathComponent = explode("/", trim($path, "/"));

$endpoint = $pathComponent[1];

switch($endpoint){
		
	case 'search':
		include('search.php');

		break;
	case 'insert':
		include('insert.php');
		
		break;
	default:
		$output[] = 'Status: Error';
		$output[] = 'MSG: '.$endpoint.' Not Found';
		$output[] = 'Action: None';
		$responseData = json_encode($output);
		echo $responseData;
}
?>