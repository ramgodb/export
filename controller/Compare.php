<?php
class controlCompare extends modelCompare
{
	public function __construct()
	{
		parent::__construct();
		$this->sessionid = time();
		$this->file = ROOT_PATH . "assets/compare.json";
		$this->emailTo = SITE_EMAIL; 
		$this->emailCc = SITE_EMAIL_SUPPORT;
	}

	public function getlist() {
		
	}

	public function set() {
		$data = (object) array(
					"task" => array(
							"set1" => array(
								"name" => "MOT",
								"description" => "AnalystSorterPerSector-RM_ANALYST_SORTER",
								"source" => array(
									"database" => "COWEN_DASH",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								),
								"destination" => array(
									"database" => "Prism",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								)
							),
							"set2" => array(
								"name" => "MOT",
								"description" => "vwRMAnalystSectorTicker-RM_ANALYST_SECTOR",
								"source" => array(
									"database" => "COWEN_DASH",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								),
								"destination" => array(
									"database" => "COWEN_DASH",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								)
							),
							"set3" => array(
								"name" => "contact",
								"description" => "list",
								"source" => array(
									"database" => "COWEN_DASH",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								),
								"destination" => array(
									"database" => "Prism",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								)
							),
							"set4" => array(
								"name" => "contact",
								"description" => "list",
								"source" => array(
									"database" => "COWEN_DASH",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								),
								"destination" => array(
									"database" => "COWEN_DASH",
									"user" => "sa",
									"pass" => "ganesh",
									"server" => "VIVIAN\SQLEXPRESS",
									"query" => "SELECT count(list_id) AS cnt FROM T_D_LIST_DETAIL"
								)
							)
						)
				);
					
		var_dump($data);
		file_put_contents($this->file, json_encode($data));
		/*echo "Are you sure you want to do this?  \nType 'yes' to continue: ";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		if(trim($line) != 'yes'){
		    echo "ABORTING!\n";
		    exit;
		}
		fclose($handle);
		echo "\n"; 
		echo "Thank you, continuing...\n";*/
	}

	private function sendMail($subject, $message) {
		$cc = $this->emailCc; 
		$to = $this->emailTo;
		$message = '<div id="cowen-export-wrap" style="width: 600px;padding: 3px;border: 2px #666 solid;margin: 5px;"><h3 style="padding: 3px;background-color: #666;font-weight: bold;color:#FFF;">DEV - BCP Query check</h3><div style="padding: 3px;">Date : '.date("Y-m-d H:i:s").'</div><div style="padding: 3px;">Batch id : '.$this->sessionid.'</div></div>'.$message;
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
		$msg = '<table id="cowen-export-msg-tbl" style="width:610px; margin:5px; border:2px #666 solid;">';

		$msg .= '<tr style="background-color: #666; color:#FFF;"><th style="padding: 3px;">Sno</th><th style="padding: 3px;">Name</th><th style="padding: 3px;">Source</th><th style="padding: 3px;">Destination</th><th style="padding: 3px;">Status</th></tr>';
		$passStyle = 'style="background-color:#74A274;"';
		$failStyle = 'style="background-color:#B77676;"';
		foreach($checklist->task as $list) {
			if(strtolower($list->name) == $set) {
				$source = $list->source;
				$dest = $list->destination;
				list($compare,$src,$dst) = $this->compare($source, $dest);
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
			$msg .= "<tr><td colspan='5' align='center'>Error..</td></tr>";
			//echo "Error : Set value provided is wrong...";
		}
		$msg .= "</table>";
		$subject = "DEV - " . strtoupper($set) . " BCP Check : " . (($status == true) ? "Pass" : "Fail");
		$mail = $this->sendMail($subject, $msg);
		echo "<h3>".$subject."</h3>";
		echo $msg;
	}
}
?>