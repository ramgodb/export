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
}
?>