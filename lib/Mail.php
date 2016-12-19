<?php
class libMail
{
	public function __construct() {
		//construct default values in here
	}

	public function send_email($from,$to,$subject,$message,$cc=null,$bcc=null)
	{
		if(SEND_EMAIL === false) {
			return false;
		}
		
		if(isset($from)) {
			$pos = strpos($from, '<');
			if($pos === false)
				ini_set("sendmail_from", $_SESSION['user_name']." <".$from.">");
			else
				ini_set("sendmail_from", $from);
		}
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= $to. "\r\n";
		if(isset($from)) {
			if($pos === false)
				$headers .=$from= $_SESSION['user_name'].' <'.$from.'>' . "\r\n";
			else
				$headers .=$from= $from . "\r\n";
		}
		else {
			$headers .=$from= 'Prism Alerts <prism-alerts@cowen.com>' . "\r\n";
		}
		if(isset($cc))
			$headers .= 'Cc: '.$cc. "\r\n";
		if(isset($bcc))
			$headers .= 'Bcc: '.$bcc. "\r\n";
		//$headers .= 'Bcc: vivian@godbtech.com' . "\r\n";
		$headers .='X-Mailer: PHP/' . phpversion();
		
		$stat = @mail($to, $subject, $message, $headers);
		//if($stat) libLog::apiDBlog($from,$to,$subject,$message,"Success");
		//else libLog::apiDBlog($from,$to,$subject,$message,"Failed");
		return $stat;
	}
}
?>