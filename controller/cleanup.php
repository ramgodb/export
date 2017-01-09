<?php

class controlCleanup {

	public function __construct() {
		$this->lmuploads = "D:/ApacheHtdocs/api/php/uploads/excel/";
	}

	public function index($param) {
		$type = $param[0];
		if($type == 'lm') {
			$folder = date("dM",strtotime( '-1 days' ));
			$path = $this->lmuploads . $folder . '/';

			if(file_exists($path)) {	
				$files = array_diff(scandir($path), array('.','..')); 
				foreach ($files as $file) { 
			      (is_dir("$path/$file")) ? delTree("$path/$file") : unlink("$path/$file"); 
			    } 
			    $remdir = rmdir($path); 

				if(!$remdir) {
					echo "Could not remove $path";
				} else {
					echo "File removed successfully";
				}
			}
		}
	}

}

?>