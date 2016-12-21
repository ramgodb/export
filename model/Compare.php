<?php
class modelCompare extends libDatabase
{
	protected $sessionid;
	public function __construct() {
		parent::__construct();
	}

	protected function dashQuery($qry, $table) {
		$tblQry = "SELECT TOP 1 count FROM T_DASHBOARD_CHECK_LOG WHERE table_name='".$table."' AND date >= DATEADD(DAY, -1, GETDATE()) ORDER BY date ASC";
		$tblRow = $this->fetch_assoc($tblQry);
		$prevCount = 0;
		if(COUNT($tblRow) > 0) {
			$prevCount = $tblRow[0]['count'];
		}

		$count = array(0 => $prevCount);
		$res = $this->fetch_assoc($qry);
		$row = count($res);
		if(!empty($res) AND $res != '') {
			if($row > 1) {
				$temp = "";
				foreach($res as $result) {
					if(count($result) > 2) {
						foreach($result as $key => $val) {
							$temp .= "[ ".$key ."=".$val." ]<br />";
						}
					} else {
						$key='';
						foreach($result as $val) {
							if($key=='') {
								$key = $val;
							} else {
								$temp .= "[ ".$key ."=".$val." ]<br />";
								$key = '';
							}
						}
					}
				}
				$count[1] = $temp;
			} else {
				$count[1] = $res[0]['cnt'];
			}
		} else {
			$count[1] = 0;
		}
		return array($count, $row);
	}

	protected function dashLog($table,$count,$batch) {

		$ins = $this->query("INSERT INTO T_DASHBOARD_CHECK_LOG (batch_name, batch_id, table_name, count, date) VALUES ('$batch', '$this->sessionid', '$table', '$count', GETDATE())");
		return $ins;
	}

	protected function compare($sourceSet, $destSet) {
		$server = $sourceSet->server;
		$user = $sourceSet->user;
		$pass = $sourceSet->pass;
		$database = $sourceSet->database;
		$query1 = $sourceSet->query;
		$db1 = new libDatabase($server, $database, $user, $pass);
		$val1 = $db1->fetch_array($query1);

		$server = $destSet->server;
		$user = $destSet->user;
		$pass = $destSet->pass;
		$database = $destSet->database;
		$query2 = $destSet->query;
		$db2 = new libDatabase($server, $database, $user, $pass);
		$val2 = $db2->fetch_array($query2);

		$staus = 0;
		if($val1[0][0] > $val2[0][0]) {
			$staus = 1;
		} elseif($val1[0] < $val2[0]) {
			$staus = 2;
		} 
		$db1 = $db2 = null;
		return array($staus,$val1[0][0],$val2[0][0]);
	}

	protected function log($name, $desc, $source, $dest, $source_count, $dest_count, $status) {
		$desc = $this->encode_string($desc);
		$source_str = $this->encode_string(json_encode($source));
		$dest_str = $this->encode_string(json_encode($dest));
		$ins = $this->query("INSERT INTO API_BCP_LOGS (sessionid, name, description, source, source_count, destination, dest_count, datetime, status) VALUES ('".$this->sessionid."','".$name."','".$desc."','".$source_str."','".$source_count."','".$dest_str."','".$dest_count."',GETDATE(),".$status.")");
		return $ins;
	}
}
?>