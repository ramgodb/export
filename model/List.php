<?php

class modelList extends libDatabase
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
		$query="select contact_id,name,username,password,sponcer_name,sponcer_id from T_CONTACT_CREDENTIAL where mail_status=0";
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
			$sponcer_id=$result[$i]['sponcer_id'];
			$sponcer_name=$result[$i]['sponcer_name'];
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
				$sp_query="select Email from EMPLOYEE_SUMMARY where HREmpID='$sponcer_id'";
				$sp_result = $this->fetch_assoc($sp_query);
				//$sp_to=$sp_result[0]['Email'];
				$sp_to='mkarthikeyan@godbtech.com';
				$sp_subject="Cowen Research Library Access Granted";
				$sp_body="<html>
					<body>
						<table style='font-family: Helvetica , sans-serif; font-size: 12px;'>
							<tr><td>Dear ".$sponcer_name.",</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Access to Cowen's equity research is granted to ".$contact_name.".</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Sincerely yours,<br>Robert Fagin <br>Director of Research <br>Cowen and Company </td></tr>
							<tr><td>&nbsp;</td></tr>
						</table>
					</body>
				</html>";
				if($mail->send_email(null,$sp_to,$sp_subject,$sp_body,$sp_cc))
				{
					$email_query="insert into T_EMAIL_LOG (email_from,email_to,email_subject,email_content,from_ip,user_id,email_status) values ('Prism Alert<prism-alerts@cowen.com>','$sp_to','$sp_subject','$sp_body','Local','prism', 'Success')";
					$email_result = $this->query($email_query);
					if (PHP_SAPI === 'cli') 
						fwrite(STDERR, "Mail sent to ".$sponcer_name." successfully...\r\n");
				}
				else {
					$email_query="insert into T_EMAIL_LOG (email_from,email_to,email_subject,email_content,from_ip,user_id,email_status) values ('Prism Alert<prism-alerts@cowen.com>','$sp_to','$sp_subject','$sp_body','Local','prism', 'Failed')";
					$email_result = $this->query($email_query);
					if (PHP_SAPI === 'cli') 
						fwrite(STDERR, "Mail sending failed...\r\n");
				}
				return true;
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
	
	protected function listData() {
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Generating data in model listData()...\r\n");
		$mappQry = "SELECT LH.list_id, S.HREmplID AS analyst_id, S.FirstName, S.LastName, LD.contact_name, LD.acc_name, LD.contact_phone_1, LD.contact_phone_2, LD.contact_email, LD.contact_id FROM
						T_D_LIST_HEADER LH INNER JOIN T_D_LIST_DETAIL LD ON LH.list_id = LD.list_id
						INNER JOIN EMPLOYEE_SUMMARY S ON LH.emp_id= S.HREmplID
						WHERE LH.locked_status = '1' 
						ORDER BY LD.contact_name ASC";
		$mappRow = $this->fetch_assoc($mappQry);
		
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Data's collected successfully...\r\n");
		return $mappRow;
	}
}
?>