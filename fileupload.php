<?php
if (PHP_SAPI != 'cli') {
	echo "Invalid Access...";
}

include_once('global/config.php');
/* force UTC as default time format */
date_default_timezone_set ("UTC");

set_include_path(get_include_path() . PATH_SEPARATOR . 'plugins/phpseclib');
include('Net/SSH2.php');
include('Net/SFTP.php');

$output = array('error' => false, 'msg' => '');

$dest = (isset($argv[1]) ? $argv[1] : 'bm');

if($dest == 'cia') {
	
	$destination = "COWEN".date('mdy').".txt";
	$source = './assets/export/'.$destination;
	if(!file_exists($source)) {
		$output['error'] = true;
		$output['msg'] = "Source file not available...";
	} else {
		//if(strtolower(APP) == 'prod') {
			$sftp = new Net_SFTP(BM_LIST_SFTP_HOST,22);
			if (!$sftp->login(BM_LIST_SFTP_USER, BM_LIST_SFTP_PASS)) { 
				$output['error'] = true;
				$output['msg'] = "sftp Login Failed...";
			} else {
				$result = $sftp->put('List Upload/'.$destination, $source, NET_SFTP_LOCAL_FILE);
				
				if($result) {
					//echo "file write success";
					$output['error'] = false;
					$output['msg'] = "file write success...";
				} else {
					//echo "file write failed";
					$output['error'] = true;
					$output['msg'] = "file write failed...";
				}
			}
		//}
	}
	
} else {

	$destination = BM_SFTP_PATH . "BMEntitlements.txt";
	$source = "./assets/export/" . date('dMY') .'-COWEN-BM'. '.txt';

	if(!file_exists($source)) {
		$output['error'] = true;
		$output['msg'] = "Source file not available...";
	} else {
		if(strtolower(APP) == 'prod') {
			$sftp = new Net_SFTP(BM_SFTP_HOST,22);
			if (!$sftp->login(BM_SFTP_USER, BM_SFTP_PASS)) { 
				$output['error'] = true;
				$output['msg'] = "sftp Login Failed...";
			} else {
				$result = $sftp->put($destination, $source, NET_SFTP_LOCAL_FILE);
				if($result) {
					//echo "file write success";
					$output['error'] = false;
					$output['msg'] = "file write success...";
				} else {
					//echo "file write failed";
					$output['error'] = true;
					$output['msg'] = "file write failed...";
				}
			}
		} else {
			$output['error'] = false;
			$output['msg'] = "file write success...";
		}
	}
}
echo json_encode($output);
?>