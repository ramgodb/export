<?php
class controlCheck
{
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
	public function apache() {
		$pageurl = HTTP_PATH;
		$filename = "information.php";

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
		var_dump($info);
	}
}
?>