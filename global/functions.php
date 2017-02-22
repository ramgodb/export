<?php

/******
 * Sample function to display array in format
 */
function print_array($arr, $exit = true) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
	if($exit) 
		exit;
}
?>