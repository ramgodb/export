<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'plugins/phpseclib');
include('Net/SSH2.php');
include('Net/SFTP.php');

$output = array('error' => false, 'msg' => '');
$destination = "/incoming/BMEntitlements.txt";
//$checkid = trim($_REQUEST['id']);
$source = "./assets/export/" . date('dMY') .'-COWEN-BM'. '.txt'; //trim($_REQUEST['file']);

/* if($checkid != $_SESSION['export_upload']) {
	$output['error'] = true;
	$output['msg'] = "Illeagel access...";
} elseif($checkid == '' OR $source == '') {
	$output['error'] = true;
	$output['msg'] = "Empty values...";
} else */if(!file_exists($source)) {
	$output['error'] = true;
	$output['msg'] = "Source file not available...";
} else {
	$sftp = new Net_SFTP('ftp.bluematrix.com',22);
	if (!$sftp->login('cowendev', 'db5RM0wk-')) { //if you can't log on...
		//exit('sftp Login Failed');
		$output['error'] = true;
		$output['msg'] = "sftp Login Failed...";
	} else {
		$output = $sftp->put($destination, $source, NET_SFTP_LOCAL_FILE);
		if($output) {
			//echo "file write success";
			$output['error'] = false;
			$output['msg'] = "file write success...";
		} else {
			//echo "file write failed";
			$output['error'] = true;
			$output['msg'] = "file write failed...";
		}
	}
}
echo json_encode($output);
?>