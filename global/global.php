<?php

include('config.php');

/*****
 * Global functions
 **/
include('init.php');
include('functions.php');

/*****
 * Route macanism
 **/
function doAction($urlExp) {
	if(count($urlExp) == 1) {
		$class = "control" . ucfirst(strtolower($urlExp[0]));
		if (class_exists($class)) {
			$cla = new $class;
			$cla->index();
		} else {
			error_log("class name could not be resolved ". $class);
			throw new Exception("Invalid class name ... ". $class);
			exit;
		}
	} elseif(count($urlExp) == 2) {
		$class = "control" . ucfirst(strtolower($urlExp[0]));
		if (class_exists($class)) {
			$cla = new $class;
			$cla->$urlExp[1]();
		} else {
			error_log("class name could not be resolved ". $class);
			throw new Exception("Invalid class name ... ". $class);
			exit;
		}
	} elseif(count($urlExp) > 2) {
		$class = "control" . ucfirst(strtolower($urlExp[0]));
		if (class_exists($class)) {
			$cla = new $class;
			$param = array();
			for($i = 2; $i<count($urlExp);$i++) {
				$param[] = $urlExp[$i];
			}
			$cla->$urlExp[1]($param);
		} else {
			error_log("class name could not be resolved ". $class);
			throw new Exception("Invalid class name ... ". $class);
			exit;
		}
	} else {
		error_log("Wrong URL format.. [" . $requested_url . "]");
		throw new Exception("Requested URL not found.... [".$requested_url."]");
	}
}