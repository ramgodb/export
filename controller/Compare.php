<?php
class controlCompare extends modelCompare
{
	public function __construct()
	{
		parent::__construct();
		$this->sessionid = time();
		$this->file = ROOT_PATH . "assets/compare.json";
		$this->dashFile = ROOT_PATH . "assets/dashboard.json";
		$this->emailTo = SITE_EMAIL; 
		$this->emailCc = SITE_EMAIL_SUPPORT;
	}

	public function index($param = array()) {
		$action = $param[0];
		$mail = false;
		if($action == 'email') {
			$res = $this->check($action);
			if($res['email'] > 0) {
				$mail = true;
			}
		} elseif($action == 'salesforce') {
			$res = $this->check($action);
			if($res['salesforce'] > 0) {
				$mail = true;
			}
		} elseif($action == 'all') {
			$res = $this->check($action);
			$res[$action] = $res['email'] . '--' . $res['salesforce'];
			if($res['email'] > 0 || $res['salesforce'] > 0) {
				$mail = true;
			}
		} else {
			echo "Invalid parameter...";exit;
		}

		if($mail) {
			$msg = '<div id="cowen-export-wrap" style="width: 300px;padding: 3px;border: 2px #666 solid;margin: 5px;"><h3 style="padding: 3px;background-color: #666;font-weight: bold;color:#FFF;">'.APP.' - Query check</h3><div style="padding: 3px;">Date : '.date("Y-m-d H:i:s").'</div>';
			if($action == 'all') {
				$msg .= '<div style="padding: 3px;">Email : '.(($res['email'] > 0) ? 'Fail' : 'Pass').'</div><div style="padding: 3px;">Un processed rows : '.$res['email'].'</div><div style="padding: 3px;">Salesforce : '.(($res['salesforce'] > 0) ? 'Fail' : 'Pass').'</div><div style="padding: 3px;">Un processed rows : '.$res['salesforce'].'</div>';
			} else {
				$msg .= '<div style="padding: 3px;">'.ucfirst($action).' : '.(($res[$action] > 0) ? 'Fail' : 'Pass').'</div><div style="padding: 3px;">Un processed rows : '.$res[$action].'</div>';
			}
			$msg .= '</div>';
			$subject = 	APP ." - " . strtoupper($action) . " Query check : Fail";
			$mail = $this->sendMail($subject, $msg);
		}
		echo "Success...\r\n" . $action ." ".$res[$action];
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

	public function init($set = '') {
		$set = strtolower($set[0]);
		$filename = $this->file;
		$jsonData = file_get_contents($filename);
		$checklist = json_decode($jsonData);

		if($set == '' OR $set == null) {
			echo "Error : Please provide set value to run the compare...";exit;
		}
		$available = false;
		$status = true;
		$c=1;
		$msg = '<div id="cowen-export-wrap" style="width: 900px;padding: 3px;border: 2px #666 solid;margin: 5px;"><h3 style="padding: 3px;background-color: #666;font-weight: bold;color:#FFF;">'.APP.' - BCP Query check</h3><div style="padding: 3px;">Date : '.date("Y-m-d H:i:s").'</div><div style="padding: 3px;">Batch id : '.$this->sessionid.'</div></div>';
		$msg .= '<table id="cowen-export-msg-tbl" style="width:910px; margin:5px; border:2px #666 solid;">';

		$msg .= '<tr style="background-color: #666; color:#FFF;"><th style="padding: 3px;">Sno</th><th style="padding: 3px;">Name</th><th style="padding: 3px;">Source</th><th style="padding: 3px;">Destination</th><th style="padding: 3px;">Status</th></tr>';
		$passStyle = 'style="background-color:#74A274;"';
		$failStyle = 'style="background-color:#B77676;"';
		foreach($checklist->task as $list) {
			if(strtolower($list->name) == $set) {
				$source = $list->source;
				$dest = $list->destination;
				list($compare,$src,$dst) = $this->compare($source, $dest);
				$src = number_format($src,0,'.',',');
				$dst = number_format($dst,0,'.',',');
				if($compare == 1) {
					$status = false;
					//echo "Compare Fail : Source data higher then destination";
					$msg .= "<tr $failStyle><td>".$c."</td><td>".$list->description."</td><td>".$src."</td><td>".$dst."</td><td>Fail</td></tr>";
				} elseif($compare == 2) {
					$status = false;
					//echo "Compare Fail : Source data lesser then destination";
					$msg .= "<tr $failStyle><td>".$c."</td><td>".$list->description."</td><td>".$src."</td><td>".$dst."</td><td>Fail</td></tr>";
				} elseif($compare == 0) {
					//echo "Compare Pass : Data's are equal...";
					$msg .= "<tr $passStyle><td>".$c."</td><td>".$list->description."</td><td>".$src."</td><td>".$dst."</td><td>Pass</td></tr>";
				} else {
					$status = false;
					//echo "Error : Something went wrong...";
					$msg .= "<tr $failStyle><td colspan='5' align='center'>Error : Something went wrong...</td></tr>";
				}
				$available = true;
				$c++;
				$this->log($list->name, $list->description, $source, $dest, $src, $dst, $compare);
			}
		}
		if(!$available) {
			$msg .= "<tr><td colspan='5' align='center'>No data available....</td></tr>";
			//echo "Error : Set value provided is wrong...";
			echo "No data available....\r\n";
		}
		$msg .= "</table>";
		$subject = APP . " - " . strtoupper($set) . " BCP Check : " . (($status == true) ? "Pass" : "Fail");
		$mail = $this->sendMail($subject, $msg);
		//echo "<h3>".$subject."</h3>";
		//echo $msg;
		if($mail) {
			echo "Mail success....\r\n";
		}
	}

	public function dashboard($batch = '') {
		$set = strtolower($batch[0]);
		$filename = $this->dashFile;
		$jsonData = file_get_contents($filename);
		$checklist = json_decode($jsonData);

		if($set == '' OR $set == null) {
			echo "Error : Please provide batch value to run the count...";exit;
		}
		
		if(!isset($checklist->$set)) {
			echo "Error : Given batch $set is wrong...";exit;
		}
		$batch = $checklist->$set;

		$available = false;
		$status = true;
		$c=1;
		$msg = '<div id="cowen-export-wrap" style="width: 900px;padding: 3px;border: 2px #666 solid;margin: 5px;"><h3 style="padding: 3px;background-color: #666;font-weight: bold;color:#FFF;">'.APP.' - Dashboard Query check</h3><div style="padding: 3px;">Date : '.date("Y-m-d H:i:s").'</div><div style="padding: 3px;">Batch id : '.$this->sessionid.'</div><div style="padding: 3px;">Batch name : '.$set.'</div></div>';

		$msg .= '<table id="cowen-export-msg-tbl" style="width:910px; margin:5px; border:2px #666 solid;">';
		$msg .= '<tr style="background-color: #666; color:#FFF;"><th style="padding: 3px;">Sno</th><th style="padding: 3px;">Table</th><th style="padding: 3px;">Prev Count</th><th style="padding: 3px;">Today Count</th><th style="padding: 3px;">Status</th></tr>';
		$passStyle = 'style="background-color:#74A274;"';
		$failStyle = 'style="background-color:#B77676;"';
		foreach($batch as $list) {
			list($count, $row) = $this->dashQuery($list->query, $list->table);
			$log_count = $count[1];
			if(is_numeric($count[1])) {
				$count[1] = number_format($count[1],0,'.',',');
				$count[0] = number_format($count[0],0,'.',',');
			}
			if(((is_numeric($count[1]) AND $count[1] > 0) OR (!is_numeric($count[1]) AND $count[1] != '')) AND $list->rows == $row) {
				$msg .= "<tr $passStyle><td>".$c."</td><td>".$list->table."</td><td>".$count[0]."</td><td>".$count[1]."</td><td>Pass</td></tr>";
			} else {
				$status = false;
				$msg .= "<tr $failStyle><td>".$c."</td><td>".$list->table."</td><td>".$count[0]."</td><td>".$count[1]."</td><td>Fail</td></tr>";
			}
			$available = true;
			$c++;
			$this->dashLog($list->table, $log_count, $set);
		}
		if(!$available) {
			$msg .= "<tr><td colspan='5' align='center'>Error..</td></tr>";
			//echo "Error : Set value provided is wrong...";
			echo "No data available....\r\n";
		}
		$msg .= "</table>";
		$subject = 	APP ." - " . strtoupper($set) . " DASHBOARD Check : " . (($status == true) ? "Pass" : "Fail");
		$mail = $this->sendMail($subject, $msg);
		//echo $msg;
		if($mail) {
			echo "Mail success....\r\n";
		}
	}
}
?>