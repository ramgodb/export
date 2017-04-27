<<<<<<< HEAD
=======
<?php
class modelCompare extends libDatabase
{
	protected $sessionid;
	public function __construct() {
		parent::__construct();
	}

	protected function check($action) {
		$emailQry = "SELECT COUNT(bulk_email_id) AS cnt FROM T_BULK_EMAIL WHERE email_date > DATEADD(MINUTE, -16, GETDATE()) AND email_date < DATEADD(MINUTE, -10, GETDATE()) AND complete_datetime is null";
		$sfQry = "SELECT COUNT(id) AS cnt FROM T_SALESFORCE_UPDATE WHERE date > DATEADD(MINUTE, -16, GETDATE()) AND date < DATEADD(MINUTE, -10, GETDATE()) AND (sf_id is null OR sf_updated_date is null OR sf_response is null)";
		
		if($action == 'email') {
			$emailRow = $this->fetch_assoc($emailQry);
		} elseif($action == 'salesforce') {
			$sfRow = $this->fetch_assoc($sfQry);
		} else {
			$emailRow = $this->fetch_assoc($emailQry);
			$sfRow = $this->fetch_assoc($sfQry);
		}
		$output = array();
		$output['email'] = (isset($emailRow[0]['cnt']) ? $emailRow[0]['cnt'] : null);
		$output['salesforce'] = (isset($sfRow[0]['cnt']) ? $sfRow[0]['cnt'] : null);
		return $output;
	}
	
	public function sfInterest(){
		
		$sfInterestSubTypeAtt = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_sub_type_attr HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestSubTypeAttRow = $this->fetch_assoc($sfInterestSubTypeAtt);
		$output = array();
		if(count($sfInterestSubTypeAttRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_sub_type_attr';
			array_push($output, $arrVal);
		}
		
		$sfInterestSubType = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_sub_type HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestSubTypeRow = $this->fetch_assoc($sfInterestSubType);
		$output = array();
		if(count($sfInterestSubTypeRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_sub_type';
			array_push($output, $arrVal);
		}
		
		$sfInterestAtt = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_attr HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestAttRow = $this->fetch_assoc($sfInterestAtt);
		$output = array();
		if(count($sfInterestAttRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_attr';
			array_push($output, $arrVal);
		}
		
		$sfInterestSub = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_sub HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestSubRow = $this->fetch_assoc($sfInterestSub);
		$output = array();
		if(count($sfInterestSubRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_sub';
			array_push($output, $arrVal);
		}
		
		$sfInterest = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestRow = $this->fetch_assoc($sfInterest);
		$output = array();
		if(count($sfInterestRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest';
			array_push($output, $arrVal);
		}
		
		$sfInterestSponsor = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_sponsor HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestSponsorRow = $this->fetch_assoc($sfInterestSponsor);
		$output = array();
		if(count($sfInterestSponsorRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_sponsor';
			array_push($output, $arrVal);
		}
		
		$sfInterestBmDType = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_bm_doc_type HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestBmDTypeRow = $this->fetch_assoc($sfInterestBmDType);
		$output = array();
		if(count($sfInterestBmDTypeRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_bm_doc_type';
			array_push($output, $arrVal);
		}
		
		$sfInterestSubCoverage = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_sub_coverage HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestSubCoverageRow = $this->fetch_assoc($sfInterestSubCoverage);
		$output = array();
		if(count($sfInterestSubCoverageRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_sub_coverage';
			array_push($output, $arrVal);
		}
		
		$sfInterestSubTypeAttBMType = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_sub_type_attr_bmtype HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestSubTypeAttBMTypeRow = $this->fetch_assoc($sfInterestSubTypeAttBMType);
		$output = array();
		if(count($sfInterestSubTypeAttBMTypeRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_sub_type_attr_bmtype';
			array_push($output, $arrVal);
		}
		
		$sfRecordType = "select MAX(LastModifiedDate) as last_update from dv_sf_recordtype HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfRecordTypeRow = $this->fetch_assoc($sfRecordType);
		$output = array();
		if(count($sfRecordTypeRow) == 0){
			$arrVal['tblname'] = 'dv_sf_recordtype';
			array_push($output, $arrVal);
		}
		
		$sfInterestAttType = "select MAX(LastModifiedDate) as last_update from dv_sf_base_interest_attribute_type HAVING convert(datetime, cast(replace(MAX(LastModifiedDate), 'T', ' ') as varchar(19)), 120) > DATEADD(HOUR, -6, GETDATE()) ";
		$sfInterestAttTypeRow = $this->fetch_assoc($sfInterestAttType);
		$output = array();
		if(count($sfInterestAttTypeRow) == 0){
			$arrVal['tblname'] = 'dv_sf_base_interest_attribute_type';
			array_push($output, $arrVal);
		}
		
		return $output;
		
	}
	
	

	protected function dashQuery($qry, $table) {
		$tblQry = "SELECT TOP 1 count FROM T_DASHBOARD_CHECK_LOG WHERE table_name='".$table."' AND date < CAST(GETDATE() AS date) ORDER BY date DESC";
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
							$temp .= "[ ".$key ."=".number_format($val,0,'.',',')." ]<br />";
						}
					} else {
						$key='';
						foreach($result as $val) {
							if($key=='') {
								$key = $val;
							} else {
								$temp .= "[ ".$key ."=".number_format($val,0,'.',',')." ]<br />";
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
		$diff = intval($val1[0][0]) - intval($val2[0][0]);
		if($diff > 10) {
			$staus = 1;
		} elseif($diff < -10) {
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
>>>>>>> 2e3ad57ebc6f3c2e6224f11f013b46f2c13def7e
