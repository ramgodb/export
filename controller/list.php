<?php
class controlList extends modellist
{
	public function __construct()
	{
			parent::__construct();
			
			$this->separator = "|";
			$this->newline = "\r\n";
			$this->path = ROOT_PATH . 'assets/export/'; 
			$this->emailTo = SITE_EMAIL; 
			$this->emailCc = SITE_EMAIL_SUPPORT;
			$this->sftpFile = "/incoming/BMEntitlements.txt";
	}
	
	/******
	 * --Not derived yet--
	 ******/
	private function sendMail($status, $data) {
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Sending mail...\r\n");
		$cc = $this->emailCc; 
		$to = $this->emailTo;
		$sub = APP . ' BBM List Management file update : ' . $status;
		$body = $data;
		$mail = new libMail();
		if($mail->send_email(null,$to,$sub,$body,$cc))
		{
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Mail sent successfully...\r\n");
			return true;
		}
		else{
			//echo "<br>mail fail";
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Mail sending failed...\r\n");
			return false;
		}
	}

	/******
	 * Give file details like exsits, size, line inside, path
	 * @param : string=> filename with sub directory : /BM/sample.txt 
	 * @return : array();
	 ******/
	private function getFileDetails($filename) {
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Getting file info $filename ...\r\n");
		$info = array();
		$file = $filename;
		$info['exists'] = file_exists($file);
		if($info['exists']) {
			$info['size'] = filesize($file);
			$info['size'] = $info['size'] / 1024;
			$info['last_accessed'] = date('Y-m-d h:m:i A',fileatime($file));
			$info['last_modified'] = date('Y-m-d h:m:i A',filemtime($file));
			$info['type'] = filetype($file);
			//Get number of lines
			$f = fopen($file, 'rb');
		    $lines = 0;
		    while (!feof($f)) {
		        $lines += substr_count(fread($f, 8192), "\n");
		    }
		    fclose($f);
			$info['lines'] = number_format($lines,0,'.',',');
		} else {
			$info['size'] = 0;
			$info['last_accessed'] = '';
			$info['last_modified'] = '';
			$info['type'] = '';
			$info['lines'] = 0;
		}
		
		return $info;
	}
	
	/******
	 * Add [tab] seprate between two values for a row
	 * @param : array => list of a row data
	 * @return : string
	 ******/
	private function addSeparator($array) {
		$string = '';
		if(is_array($array) AND count($array) > 0) {
			$string .= implode($this->separator, $array);
			$string .= $this->newline;
		}
		return $string;
	}

	/******
	 * Apend a row of string to given destination
	 * @param1 : string => full path of destination file
	 * @param2 : string => fully formated string to append to destination file
	 ******/
	private function appendData($dest, $data) {
		try {
			// Write the contents to the file, 
			// using the FILE_APPEND flag to append the content to the end of the file
			// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
			$write = file_put_contents($dest, $data, FILE_APPEND | LOCK_EX);
			if(!$write) {
				throw new Exception("File data append error on file : ".$dest);
			}
		}catch(Exception $e) {
			echo "Error in file generation\r\n";
			error_log("Prefer file-error: ".$e->getMessage());
		}
	}
	
	private function searchInFile($searchfor, $file) {
		$contents = file_get_contents($file);
		$pattern = preg_quote($searchfor, '/');
		$pattern = "/^.*$pattern.*\$/m";

		if(preg_match_all($pattern, $contents, $matches)){
			return $matches[0];
		}
		else{
			return false;
		}
	}
	
	/******
	 * update given data into file
	 * @param1 : string => filename where it going to update
	 * @param2 : array => data array having head and body
	 * @param3 : string => subfolder
	 ******/
	private function updateFile($file, $dataArray, $to) {
		if (!file_exists($this->path)) {
			$dir_stat = mkdir($this->path, 0777);
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Creating folder success ..." .$this->path. " \r\n");
		}
		else if(is_dir($this->path)) {
			$dir_stat = 1;
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Folder path found ...".$this->path." \r\n");
		}
		else {
			$dir_stat = 0;
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Line number ".__LINE__.$this->path." Creating folder failed ...\r\n");
		}
		if($dir_stat == 1) {
			$destination = $this->path . $file;
			$fileInfo = $this->getFileDetails($destination);		
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "File writing progress...\r\n");
			if($fileInfo['exists']) {
				unlink($destination);
			}
			if(!empty($dataArray['head'])) {
				$data = $this->addSeparator($dataArray['head']);
				$this->appendData($destination, $data);
			}
			if(count($dataArray['body']) > 0) {
				foreach($dataArray['body'] as $arr) {
					$data = $this->addSeparator($arr);
					$insert = true;
					if($insert == true) {
						$this->appendData($destination, $data);
					}
				}
			}
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "File writing completed...\r\n");
			$fileInfoNew = $this->getFileDetails($destination);
			if($fileInfoNew['exists'] AND $fileInfoNew['size'] >= $fileInfo['size']) {
				return $fileInfoNew;
			}
			return false;
		}
		else {
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Line number ".__LINE__." ".$this->path." Creating folder failed ...\r\n");
		}
	}
	
	public function generate() {
		
		$time = microtime();
		
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Starting the process...\r\n");
		
		$dataArray = array();
		$filename = "List_".date('dMY') .'-COWEN-BM'. '.txt';

		$dataArray['head'] = array();
		//$dataArray['head'] = array("ListId", "TraderId", "UserName", "FirstName", "LastName", "AccountName",  "PhoneNumber1",  "PhoneNumber2", "Email", "ContactId");
		$dataArray['body'] = array();
		
		$bodyArray = $this->listData();
		if(count($bodyArray) > 0) {
			foreach ($bodyArray as $key => $valArray) {
				$temp = array();
				$cia_user_id = $valArray['cia_user_id'];
				$analyst_id = $valArray['analyst_id'];
				$analyst_fname = $valArray['FirstName'];
				$analyst_lname = $valArray['LastName'];
				$analyst_full_name = $analyst_fname." ".$analyst_lname;
				$contact_lname = $valArray['contact_name'];
				$account_name = $valArray['acc_name'];
				$contact_phno1 = $valArray['contact_phone_1'];
				$contact_phno2 = $valArray['contact_phone_2'];
				$contact_email = $valArray['contact_email'];
				$contact_id = $valArray['contact_id'];
				
				$temp[] = $cia_user_id;
				$temp[] = $analyst_id;
				$temp[] = $analyst_full_name;
				$temp[] = $contact_fname;
				$temp[] = $contact_lname;
				$temp[] = $account_name;
				$temp[] = $contact_phno1;
				$temp[] = $contact_phno2;
				$temp[] = $contact_email;
				$temp[] = $contact_id;
				$dataArray['body'][] = $temp;
				unset($temp);
			}
			if (PHP_SAPI === 'cli') 
				fwrite(STDERR, "Data's arranged successfully...\r\n");
			$gen = $this->updateFile($filename, $dataArray, 'cia');
			unset($dataArray);
			$time1 = microtime();
			$exec_time = $time1 - $time;

			$httppath = HTTP_PATH . 'assets/export/' . $filename;

			$msg = '<div style="width:600px;border:2px #CCC solid;padding:5px;">';
			$msg .= '<h3>File write Status...</h3>';
			$msg .= '<p><b>Time taken to update :</b> '.$exec_time.'<p>';
			$msg .= '<p><b>Filename :</b> '.$filename.'<p>';
			$msg .= '<p><b>Filesize :</b> '.$gen['size'].'<p>';
			$msg .= '<p><b>Total lines :</b> '.$gen['lines'].' KB<p>';
			$msg .= '<p><b>File Path :</b> http://192.168.0.69:84/api/v1/preference/BM/'.$filename.'<p>';
			$msg .= '</div>';

			if($gen) {
				$this->sendMail('success', $msg);
				echo "Time taken to update : $exec_time<br>";
				echo "File write Success!!!\n";
			} else {
				$this->sendMail('fail', $msg);
				echo "File write Failed!!!";
			}
		}
		else {
			echo "No data to write the file!!!";
		}
	}
	
	public function contact_email(){
		//$query="select contact_id,username,password from T_CONTACT_CREDENTIAL where mail_status=0";
		$result = $this->reqLib();
	}
}
?>