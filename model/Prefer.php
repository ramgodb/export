<?php

class modelPrefer extends libDatabase
{

	public function __construct() {
		parent::__construct();
		$this->subscription = array('Senior Analyst' => 'AU', 'Covered Ticker' => 'CO', 'Industry' => 'IN', 'Sector' => 'IN', 'Product' => 'EL');
	}

	private function unique($str) {
		return implode(',',
				array_filter(
					array_unique(
						explode(',',$str)
						)
					)
				);
	}
	
	protected function reqLib() {
		$query="select contact_id,name,username,password from T_CONTACT_CREDENTIAL where mail_status=0";
		$result = $this->fetch_assoc($query);
		//print_r($result);
		for($i=0;$i<count($result);$i++)
		{
			//$to=$result[$i]['username'];
			$to='mkarthikeyan@godbtech.com';
			$contact_id=$result[$i]['contact_id'];
			$contact_name=$result[$i]['name'];
			$username=$result[$i]['username'];
			$password=$result[$i]['password'];
			$subject="Cowen Research Library";
			$body="<html>
					<body>
						<table style='font-family: Helvetica , sans-serif; font-size: 12px;'>
							<tr><td>Dear ".$contact_name.",</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Thank you from registering for access to Cowen's equity research.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Here is your temporary login information. </td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Username:".$username."</td></tr>
							<tr><td>Password:".$password."</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>You may log on to the desktop version and change your password by clicking on *Preferences* in the left-hand menu bar (your password cannot be changed in the iPad app).</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Your user name and password allows you to access Cowen's research library from your personal computer or from an iPad. </td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>To access the desktop version, please click here: <a href='https://cowenlibrary.bluematrix.com/client/library.jsp'>https://cowenlibrary.bluematrix.com/client/library.jsp</a> </td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>To download Cowen's research app for the iPad, please click here</td></tr>
							<tr><td><a href='https://itunes.apple.com/us/app/cowen-research/id897787610?mt=8&ign-mpt=uo=4'><img src='https://conferences.cowen.com/webforms/ExternalImg/bmIpad.png'></a></td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>If you have any questions, please contact your Cowen representative.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>We thank you for your business and trust, and welcome any feedback you may have. </td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Sincerely yours,<br>Robert Fagin <br>Director of Research <br>Cowen and Company </td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Follow us on Twitter: @CowenResearch</td></tr>
						</table>
					</body>
				</html>";
			$body=str_replace('*','"',$body);
			$mail = new libMail();
			if($mail->send_email(null,$to,$subject,$body,$cc))
			{
				$up_query="update T_CONTACT_CREDENTIAL set mail_status=1,mail_date=getdate() where contact_id='$contact_id'";
				$up_result = $this->query($up_query);
				$body=str_replace("'","''",$body);
				$email_query="insert into T_EMAIL_LOG (email_from,email_to,email_subject,email_content,from_ip,user_id,email_status) values ('Prism Alert<prism-alerts@cowen.com>','$to','$subject','$body','Local','prism', 'Success')";
				$email_result = $this->query($email_query);
				if (PHP_SAPI === 'cli') 
					fwrite(STDERR, "Mail sent to ".$contact_name."---".$to." successfully...\r\n");
				//return true;
			}
			else{
				//echo "<br>mail fail";
				$body=str_replace("'","''",$body);
				$email_query="insert into T_EMAIL_LOG (email_from,email_to,email_subject,email_content,from_ip,user_id,email_status) values ('Prism Alert<prism-alerts@cowen.com>','$to','$subject','$body','Local','prism', 'Failed')";
				$email_result = $this->query($email_query);
				if (PHP_SAPI === 'cli') 
					fwrite(STDERR, "Mail sending failed...\r\n");
				//return false;
			}
			sleep(1);
		}
		return true;
	}
	
	protected function preferData() {
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Generating data in model preferData()...\r\n");
		//SELECT * FROM T_PM_PREFERENCE
		//SELECT * FROM T_PM_PREFERENCE_DETAILS
		//SELECT * FROM D_CONTACT_LIST_DETAIL 
		//SELECT (FirstName + '-' + LastName) AS FullName, * FROM dv_sf_contact WHERE Id = '0033000000MvmnbAAB'
		//SELECT * FROM T_CONTACT_CREDENTIAL
		//SELECT * FROM M_PM_DOC_MAPPING

		$mappQry = "SELECT cate_type_name, report_id, bmType_id, report_name FROM M_PM_DOC_MAPPING";
		$mappRow = $this->fetch_assoc($mappQry);
		unset($mappQry);
		$mappingData = array();
		foreach($mappRow as $mapp) {
			if(!isset($mappingData[$mapp['cate_type_name']])) {
				$mappingData[$mapp['cate_type_name']] = array();
			} 
			$tmp = $mappingData[$mapp['cate_type_name']];
			if(!isset($tmp[$mapp['report_id']])) {
				$tmp[$mapp['report_id']] = array();
			}
			$tmp1 = $tmp[$mapp['report_id']];
			if(!isset($tmp1[$mapp['bmType_id']]) AND $mapp['report_name'] != 'Ahead of the Curve') {
				$tmp1[] = $mapp['bmType_id'];
			}
			$tmp[$mapp['report_id']] = $tmp1;
			$mappingData[$mapp['cate_type_name']] = $tmp;
			unset($tmp);unset($tmp1);
		}
		unset($mappRow);
		//print_r($mappingData);exit;
		//changes in query
		//p.cate_type_id = p.bm_id
		
		//changes in query
		//pd.doc_type_id = pd.bm_Typeid
		/*$sql1 = "SELECT 
				p.contact_id, pd.bm_Typeid as doc_id
					FROM T_PM_PREFERENCE AS p 
					INNER JOIN T_PM_PREFERENCE_DETAILS AS pd 
						ON (p.id = pd.pref_id) 
							WHERE pd.status = 1";*/
		$sql1 = "SELECT contact_id, report_id, cate_type_name, cate_type_id, report_name FROM T_PM_PREFERENCE_DETAILS WHERE status = 1";
		$qry1Res = $this->fetch_assoc($sql1);
		unset($sql1);
		$docs = array();
		foreach ($qry1Res as $value) {
			$prod = 0;
			$docids = (isset($mappingData[$value['cate_type_name']][$value['report_id']]) ? $mappingData[$value['cate_type_name']][$value['report_id']] : array());
			if($value['report_name'] == 'Ahead of the Curve') {
				$prod = 2;
				$docids = array();
			}
			/*$docids = (isset($mappingData[$value['cate_type_name']][$value['report_id']]) ? $mappingData[$value['cate_type_name']][$value['report_id']] : array());
			$prod = (($value['report_name'] == 'Ahead of the Curve') ? 2 : 0);*/
			if(isset($docs[$value['contact_id']])) {
				$docs[$value['contact_id']]['doc'] .= ','.implode(',',$docids);
				$docs[$value['contact_id']]['product'] = $prod;
			} else {
				$docs[$value['contact_id']] = array('contact_id' => $value['contact_id'], 'doc' => implode(',',$docids), 'product' => $prod);
			}
		}
		unset($mappingData);
		unset($qry1Res);
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Doc type gathered...\r\n");
		
		$sql = "SELECT 
					p.contact_id AS id, 
					p.account_name AS institution, 
					p.cate_type_name AS subs_name, 
					p.bm_id AS subs_id, 
					p.status AS active, 
					(SELECT 
						(sfc.FirstName + '--' + sfc.LastName + '--' + sfc.Email) 
						FROM dv_sf_contact AS sfc 
							WHERE sfc.Id = p.contact_id) AS sfcname, 
					(SELECT 
						(cc.username + '--' + cc.password) 
						FROM T_CONTACT_CREDENTIAL AS cc 
							WHERE cc.contact_id = p.contact_id AND cc.status = 1) AS access
				FROM T_PM_PREFERENCE AS p 
					WHERE p.status = 1"; //CAST(p.modified_on AS DATE) = CAST(GETDATE() AS DATE) AND
					/* ,
					analyst = STUFF((
						SELECT ',' + md.bm_id
						FROM M_PM_ANALYST_SECTOR md
						WHERE p.cate_type_name = 'Sector' AND p.bm_id = md.sector_id
						FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, '') */
		$qryRes = $this->fetch_assoc($sql);
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Qery execution completed...\r\n");
		
		$resArray = array();
		if(!empty($qryRes)) {
			foreach ($qryRes as $res) {
				if(isset($resArray[$res['id']])) {
					// Subscription value update
					if($res['subs_id'] != '' AND $res['subs_name'] != 'product') {
						$subs = $resArray[$res['id']][$this->subscription[$res['subs_name']]];
						
						if($subs == '') {
							$tempRes = explode(',',$res['subs_id']);
						} else {
							$temp = explode(',',$subs);
							$temp1 = explode(',',$res['subs_id']);
							$tempRes = array_unique(array_merge($temp, $temp1));
						}
						$resArray[$res['id']][$this->subscription[$res['subs_name']]] = implode(',', $tempRes);
						/* if(!is_null($res['analyst'])) {
							$resArray[$res['id']]['AU'] = (($resArray[$res['id']]['AU'] != '') ? $resArray[$res['id']]['AU'] . ',' . $res['analyst'] : $res['analyst']);
							$tmp = explode(',',$resArray[$res['id']]['AU']);
							$tmp = array_unique($tmp);
							$resArray[$res['id']]['AU'] = implode(',',$tmp);
						} */
					} elseif($res['subs_name'] != 'product') {
						if($docs[$res['id']]['product'] > 0) {
							$resArray[$res['id']][$this->subscription[$res['subs_name']]] = 2;
						} else {
							$resArray[$res['id']][$this->subscription[$res['subs_name']]] = 0;
						}
					}
				} else {
					$res['AU'] = '';
					$res['CO'] = '';
					$res['IN'] = '';
					$res['EL'] = '';
					$res['doc'] = ((isset($docs[$res['id']]['doc']) AND $docs[$res['id']]['doc'] != '') ? $this->unique($docs[$res['id']]['doc']) : 0);
					$resArray[$res['id']] = $res;
					/* if(!is_null($res['analyst'])) {
						$res['subs_id'] = (($res['subs_id'] != '') ? $res['subs_id'] . ',' . $res['analyst'] : '');
					} */
					$resArray[$res['id']][$this->subscription[$res['subs_name']]] = $res['subs_id'];
					/* if(!is_null($res['analyst'])) {
						$resArray[$res['id']]['AU'] = (($resArray[$res['id']]['AU'] != '') ? $resArray[$res['id']]['AU'] . ',' . $res['analyst'] : $res['analyst']);
						$tmp = explode(',',$resArray[$res['id']]['AU']);
						$tmp = array_unique($tmp);
						$resArray[$res['id']]['AU'] = implode(',',$tmp);
					} */
				}
			}
		}
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Data's collected successfully...\r\n");
		return $resArray;
	}
}
?>