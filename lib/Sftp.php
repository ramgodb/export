<?php
/* echo "===sftp===\r\n";
set_include_path(ROOT_PATH . 'plugins/phpseclib');
include('Net/SSH2.php');
include('Net/SFTP.php'); */

class libSftp
{
	protected $sftp = '';
	public function __construct() {
		global $core;
		$this->sftp = $core->sftp;
	}
	
	public function upload($source, $destination) {
		$output = $this->sftp->put($source, $destination, NET_SFTP_LOCAL_FILE);
		if(!$output) {
			return false;
		}
		return true;
	}
}

?>