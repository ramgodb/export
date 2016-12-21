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
function accessDenied() {
	echo "<br><br><center>Illegal access....</center>";
}
function doAction($urlExp) {
	if(isset($urlExp[0])) {
		$class = "control" . ucfirst(strtolower($urlExp[0]));
		if (class_exists($class)) {
			$cla = new $class;
			if(isset($urlExp[1])) {
				if(method_exists($cla,$urlExp[1])) {
					if(isset($urlExp[2])) {
						for($i = 2; $i<count($urlExp);$i++) {
							$param[] = $urlExp[$i];
						}
						$cla->$urlExp[1]($param);
					} else {
						$cla->$urlExp[1]();
					}
				} else {
					if(method_exists($cla,"index")) {
						for($i = 1; $i<count($urlExp);$i++) {
							$param[] = $urlExp[$i];
						}
						$cla->index($param);
					} else {
						accessDenied();
						throw new Exception("Invalid method \"$urlExp[1]\" in class \"$class\" ... ". $class);exit;
					}
				}
			} else {
				if(method_exists($cla,"index")) {
					$cla->index();
				} else {
					accessDenied();
					throw new Exception("Invalid method \"index\" in class \"$class\" ... ". $class);exit;
				}
			}
		} else {
			accessDenied();
			throw new Exception("Invalid class name ... ". $class);exit;
		}
	} else {
		accessDenied();
	}
}