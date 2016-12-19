<?php
include('global/global.php');

if (PHP_SAPI === 'cli') {
	$urlExp = array();
	for($i = 1; $i<count($argv);$i++) {
		$urlExp[] = $argv[$i];
	}
} else {
	$requested_url = $_SERVER['REQUEST_URI'];
	$urlExpArr = explode('index.php',$requested_url);
	$urlExp = explode('/',substr($urlExpArr[1], 1));
	$urlExp = array_filter($urlExp);
}
doAction($urlExp);


?>