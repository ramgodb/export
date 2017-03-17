<?php

define("MAX_SKU",25);
define("ORD_QTY_COL",8);
define("SCHEME_COL",33);
define("DND_COL",58);


error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');


/** Include path **/
//echo $_SERVER['DOCUMENT_ROOT'] . '/lib/phpexcel/Classes';
//echo get_include_path() . PATH_SEPARATOR . '../../../Classes/';
set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
//set_include_path($_SERVER['DOCUMENT_ROOT'] . '/lib/phpexcel/Classes/PHPExcel/Reader/');
//include($_SERVER['DOCUMENT_ROOT'] . '/lib/phpexcel/Classes/PHPExcel/Reader/Excel5.php');
//include "../../../Classes/PHPExcel/Reader/Excel5.php";
/** PHPExcel_IOFactory */
include '../../../Classes/PHPExcel/IOFactory.php';
//include "../../../../db.php";
//MySQLConnect();
/*
$server="VIVIAN\SQLEXPRESS";
$port="55708";
$connectionInfo['UID'] = "sa";
$connectionInfo['PWD'] = "ganesh";
$connectionInfo = array(
			"Database"		=> "COWEN_DASH",
			"CharacterSet" 	=> "UTF-8",
			"ReturnDatesAsStrings" => 1
		);

$conn = sqlsrv_connect( $server.$port, $connectionInfo);
*/
$serverName = "VIVIAN\SQLEXPRESS"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"COWEN_DASH");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if($conn) {
echo "connected";
}
else {
echo "Not Connected";
}
/*$sql = "SELECT TOP 3 role, name FROM D_SEARCH";
$stmt = sqlsrv_query( $conn, $sql );

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      echo $row['role'].", ".$row['name']."<br />";
}*/
/*SetEnv db_server VIVIAN\SQLEXPRESS
		SetEnv db_user sa
		SetEnv db_pass ganesh
		SetEnv db_port 55708
		SetEnv java_home "C:/Program Files (x86)/Java/jdk1.7.0_11"
		SetEnv devel 1
		SetEnv sso_server "http://dev.cloware.com/gw/"
*/		
/*$this->db = libDB::newDb();
if( !$this->db || !$this->db->is_connected() )
{
	error_log("apiBase: db not available");
	$this->sendError(_ERR_DB);
}*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>PHPExcel Reader Example #02</title>

</head>
<body>

<h1>PHPExcel Reader Example #02</h1>
<h2>Simple File Reader using a Specified Reader</h2>
<?php
ini_set('memory_limit','256M');
$inputFileName = './sampleData/Analyst_Marketing_YTD_Running_2015.xlsx';

echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using PHPExcel_Reader_Excel5<br />';
//$objReader = new PHPExcel_Reader_Excel5();
//$objReader = new PHPExcel_IOFactory();
	$objReader = new PHPExcel_Reader_Excel2007();
//	$objReader = new PHPExcel_Reader_Excel2003XML();
//	$objReader = new PHPExcel_Reader_OOCalc();
//	$objReader = new PHPExcel_Reader_SYLK();
//	$objReader = new PHPExcel_Reader_Gnumeric();
//	$objReader = new PHPExcel_Reader_CSV();

$objPHPExcel = $objReader->load($inputFileName); 


echo '<hr />';

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true); 
//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save("jose.xls");
//var_dump($objPHPExcel);
//echo sizeof($sheetData);
//var_dump($sheetData);
$fileno = time();
//echo count($sheetData);
	
	
	
	
	
	
	/*$sql = "insert into D_CORPORATE_ACCESS (analyst_last_name, date, ticker, venue, venue_point, market_cap, market_cap_point, presenter, presenter_point, total_points) ".
		" values ('".$sheetData[$i]['A']."','".$sheetData[$i]['B']."','".$sheetData[$i]['C']."', 
		'".$sheetData[$i]['D']."','".$sheetData[$i]['E']."','".$sheetData[$i]['F']."',
		'".$sheetData[$i]['G']."','".$sheetData[$i]['H']."','".$sheetData[$i]['I']."','".$sheetData[$i]['J']."')";
	*/
	//for($k=2;$k<sizeof($sheetData);$k++)
	//{	
		$k=8;
		$col_cnt=count(array_values($sheetData[$k]));
		//$char = getNameFromNumber($col_cnt-6);
		for($j=3;$j<$col_cnt;$j++)
		{
			$char = getNameFromNumber($j);
			if($sheetData[$k][$char] == '')
			{
				break;
				//$col_cnt=count(array_values($sheetData[$k]));
			}
				$goal=$sheetData[$k][$char];
				$region=$sheetData[$k+1][$char];
			
				$sql = "insert into M_MARKETING_GOAL (region, goal) values('$region','$goal')";
				$row = sqlsrv_query($conn,$sql);
			//$SchemeQtyIdx = getNameFromNumber($j+SCHEME_COL);
			
			
		/*	$itemqry = "insert into ades_data_item (fileno,skuname,ordqty,freeqty,outletid) values (".
					" $fileno,'".$sheetData[2][$SalesQtyIdx]."',".$sheetData[$i]["$SalesQtyIdx"].",".
					$sheetData[$i]["$SchemeQtyIdx"].",'".$sheetData[$i]['C']."')";
			//echo $itemqry;
			mysql_query($itemqry);
		*/	
		} //exit; 
	//break;
	//}
		

if( !$row ) echo 'Added Successfully';
else echo 'Added Successfully';
	sqlsrv_close ( $conn );
/*
echo "<br>Hello Jose:".$sheetData[1]['I']."<br>";
echo "<br>Hello Jose:".$sheetData[1]['AH']."<br>";
echo "<br>Hello Jose:".$sheetData[1]['BG']."<br>";
echo "index: ".array_search("BG", array_keys($sheetData[1]));
echo "index: ".array_search("AH", array_keys($sheetData[1]));
echo "index: ".array_search("I", array_keys($sheetData[1]));

echo "Hey Jose::".getNameFromNumber(8);
*/
//var_dump($objPHPExcel);
exit(0);


//Code added by Jose to write the xls

/** Error reporting */
error_reporting(E_ALL);

/** Include path **/


/** PHPExcel */
//include 'PHPExcel/PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
/*
include 'PHPExcel/Writer/Excel2007.php';

// Create new PHPExcel object
echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

$qry = "select outlet_code as 'Outlet ID',name as 'Outlet Name',outlet_cat as 'Category',".
		"address as Address from meap_outlet";
echo $qry;

$result = mysql_query($qry);

$objPHPExcel->setActiveSheetIndex(0);
$i =1;
while($row = mysql_fetch_array($result))
{
	//var_dump($row);
	
	for($j=0;$j<4;$j++)
	{
		//echo "$j <br>";
		$idx = getNameFromNumber($j).($i+1);
		//echo $idx."<br>";
		$objPHPExcel->getActiveSheet()->SetCellValue($idx,$row[$j]);	
	}
	$i++;
}
	
// Add some data
echo date('H:i:s') . " Add some data\n";

//$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
//$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
//$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
//$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

// Rename sheet
echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";
*/
function getNameFromNumber($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}

?>
<body>
</html>