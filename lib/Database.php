<?php

class libDatabase
{
	public $db = '';
	public function __construct($host = DB_HOST, $db = DB_NAME, $user = DB_USER, $pass = DB_PASS) {
		$this->db = new PDO("sqlsrv:server=".$host."; Database=".$db, $user , $pass);
	}

	public function query($sql) {
		$qry = $this->db->prepare($sql);
		$qry->execute();
		return $qry;
	}

	public function fetch_array($query, $raw = false) {
		if(!$raw) {
			$qryRes = $this->query($query);
		} else {
			$qryRes = $query;
		}
		return $qryRes->fetchAll();
	}

	public function fetch_assoc($query, $raw = false) {
		if(!$raw) {
			$qryRes = $this->query($query);
		} else {
			$qryRes = $query;
		}
		return $qryRes->fetchAll(PDO::FETCH_ASSOC);
	}

	public function encode_string($str) {
		return str_replace("'", "''", str_replace('"', '""', $str));
	}

	public function decode_string($str) {
		return str_replace("''", "'", str_replace('""', '"', $str));	
	}
}
?>