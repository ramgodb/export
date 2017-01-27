<?php
class controlCheck
{
	public function __construct() {
		$this->emailTo = SITE_EMAIL; 
		$this->emailCc = SITE_EMAIL_SUPPORT;
	}
	public function info() {
		echo "Working fine";
	}
	public function params($param) {
		print_r($param);
	}
	public function database() {
		global $db;
		$res = $db->fetch_assoc("SELECT table_name FROM information_schema.tables");
		echo "<pre>";
		print_r($res);
		echo "</pre>";
	}
	private function sendMail($subject, $message) {
		$cc = $this->emailCc; 
		$to = $this->emailTo;
		$mail = new libMail();
		if($mail->send_email(null,$to,$subject,$message,$cc))
			return true;
		else
			return false;
	}
	public function apache() {
		$pageurl = "http://jayakaranv:Password29@192.168.101.80:84/export/information.php";
		$filename = "";

		$theurl = $pageurl.$filename;

		$headers = array(
		    'X-Requested-With: XMLHttpRequest',
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $theurl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$exec = curl_exec($ch);
		$info = curl_getinfo($ch);
		
		if($info['http_code'] != 200) {
			$msg = "<div>Apache failed....</div>";
			$subject = 	APP ." - Apche ping failed";
			$mail = $this->sendMail($subject, $msg);
		}
	}
}
?>