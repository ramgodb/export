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
	
	public function test()
	{
		echo "test";exit;
		/*$file = $this->path . date('dMY') .'-COWEN-BM'. '.txt';
		$fileInfoNew = $this->getFileDetails($file);
		echo "<pre>";print_r($fileInfoNew);echo "</pre>";exit;*/
		//$file = date('dMY') .'-COWEN-BM'. '.txt';
		/*if($this->sendFile()) {
			echo "file write success...";
		} else {
			echo "file write failed...";
		}*/
		/* $bodyArray = $this->preferData();
		echo "<pre>";		
		print_r($bodyArray);
		echo "</pre>";exit; */
		/*$msg = '<p>Checkjing the information</p>';
		$this->sendMail('Success',$msg);*/
		// $file = $this->path . 'sample.txt';
		// echo $file;
		// $data = file_get_contents($file);

		// if(preg_match( '#[\R]+#',"\r" )) {
		// 	echo "available";
		// } else {
		// 	echo "not available";
		// }

		//$frmt = explode('\n',$data);
		//echo '<textarea rows="10" cols="100">'.$data.'</textarea>';
		//$frmt = str_split($data);
		//var_dump($frmt);
	}
	
	private function sendFile() {
		/* $sftp = new libSftp();
		$send = $sftp->upload($source, $this->sftpFile);
		if(!$send) {
			return false;
		}
		return true; */
		
		//set_include_path(ROOT_PATH);
		
		$enc = time();
		$_SESSION['export_upload'] = $enc;
		
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		//$strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
		//session_write_close();

		$qry_str = '';//"?x=10&y=20";
		$ch = curl_init();
		// Set query data here with the URL
		curl_setopt($ch, CURLOPT_URL, HTTP_PATH . 'fileupload.php' . $qry_str); 

		curl_setopt($ch,CURLOPT_USERAGENT, $useragent);
		//curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		//curl_setopt($ch, CURLOPT_USERPWD, "jayakaranv:Password27");
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		$content = trim(curl_exec($ch));
		curl_close($ch);
		var_dump($content);

		return true;

		//$result = file_get_contents("./fileupload.php", FILE_USE_INCLUDE_PATH);
		//$res = json_decode($result);
		//var_dump($res);
		/*if($res->error) {
			return false;
		} else {
			return true;
		}*/
	}
	
	/******
	 * --Not derived yet--
	 ******/
	private function sendMail($status, $data) {
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Sending mail...\r\n");
		$cc = $this->emailCc; 
		$to = $this->emailTo;
		$sub = APP . ' Preference file update : ' . $status;
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
		$destination = $this->path . $file;

		$fileInfo = $this->getFileDetails($destination);
		
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "File writing progress...\r\n");
		
		if($fileInfo['exists']) {
			unlink($destination);
		}
		$data = $this->addSeparator($dataArray['head']);
		$this->appendData($destination, $data);
		
		if(count($dataArray['body']) > 0) {
			foreach($dataArray['body'] as $arr) {
				$data = $this->addSeparator($arr);
				//$searchRes = $this->searchInFile($arr[0], $destination);
				$insert = true;
				/* if($searchRes) {
					foreach($searchRes as $sRes) {
						if(trim($sRes) == trim($data)) {
							$insert = false;
						}
					}
				} */ 
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
	
	public function generate() {
		
		$time = microtime();
		
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Starting the process...\r\n");
		
		$dataArray = array();
		$filename = "List_".date('dMY') .'-COWEN-BM'. '.txt';

		$dataArray['head'] = array("ListId", "TraderId", "UserName", "FirstName", "LastName", "AccountName",  "PhoneNumber1",  "PhoneNumber2", "Email", "ContactId");
		$dataArray['body'] = array();
		
		$bodyArray = $this->listData();
		echo "<pre>";
		print_r($bodyArray);
		exit;
		foreach ($bodyArray as $key => $valArray) {
			$temp = array();
			$temp[] = '"'.$valArray['id'].'"';
			if($valArray['sfcname'] != '') {
				$sfcname = explode('--',$valArray['sfcname']);
				$temp[] = '"'.(isset($sfcname[0]) ? $sfcname[0] : '').'"';
				$temp[] = '"'.(isset($sfcname[1]) ? $sfcname[1] : '').'"';
				$temp[] = '"'.(isset($sfcname[2]) ? $sfcname[2] : '').'"';
			} else {
				$temp[] = '""';
				$temp[] = '""';
				$temp[] = '""';
			}
			$temp[] = '"'.$valArray['institution'].'"';
			$temp[] = '"254"';
			$temp[] = '"AU:'.$valArray['AU'].';CO:'.$valArray['CO'].';IN:'.$valArray['IN'].';EL:'.$valArray['EL'].';"';
			$temp[] = '"'.$valArray['doc'].'"';
			$temp[] = '"1"';
			$temp[] = '"'.$valArray['active'].'"';
			if($valArray['access'] != '') {
				$access = explode('--',$valArray['access']);
				$temp[] = '"1"';
				$temp[] = '"'.$access[0].'"';
				$temp[] = '"'.$access[1].'"';
			} else {
				$temp[] = '"0"';
				$temp[] = '""';
				$temp[] = '""';
			}

			$dataArray['body'][] = $temp;
			unset($temp);
		}
		if (PHP_SAPI === 'cli') 
			fwrite(STDERR, "Data's arranged successfully...\r\n");
		$gen = $this->updateFile($filename, $dataArray, 'BM');
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
			//echo $msg;
			echo "Time taken to update : $exec_time<br>";
			//echo '<a href="'.$httppath.'" target="_blank">'.$httppath.'</a>';
			echo "File write Success!!!\n";
		} else {
			$this->sendMail('fail', $msg);
			echo "File write Failed!!!";
		}
	}
	
	public function contact_email(){
		//$query="select contact_id,username,password from T_CONTACT_CREDENTIAL where mail_status=0";
		
		$result = $this->reqLib();
		
		
	}
}
?>