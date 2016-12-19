<?php

$core = (object) array();

/**
 * SFTP connection code
 ****/
set_include_path(ROOT_PATH . 'plugins/phpseclib');
include('Net/SSH2.php');
include('Net/SFTP.php');

$core->sftp = new Net_SFTP('ftp.bluematrix.com',22);
if (!$core->sftp->login('cowendev', 'db5RM0wk-')) { //if you can't log on...
    //exit('sftp Login Failed');
	throw new Exception('sftp Login Failed',-1);
}
 
?>