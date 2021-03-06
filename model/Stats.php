<?php
class modelStats extends libDatabase
{
	protected $sessionid;
	public function __construct() {
		parent::__construct();
	}

	public function rset($query)
	{
		$server = "NJPRISMDB01";
		$user = "iir_usr";
		$pass = "cD#4Xbb9";
		$database = "PRISM";
		//$query1 = $sourceSet->query;
		$db1 = new libDatabase($server, $database, $user, $pass);
		//$val1 = $db1->fetch_array($query1);
		$result = $db1->fetch_assoc($query);
		$db1=NULL;
		return $result;
	}
	
	public function generateExcel($data,$title,$footer,$file_name)
	{
		$logoImg = $_SERVER['DOCUMENT_ROOT']."/lib/images/prism_newlogo.png";

		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		ini_set('max_execution_time', 9000000);

		date_default_timezone_set('America/New_York');

		// if (PHP_SAPI == 'cli')
			// die('This example should only be run from a Web Browser');

		/** Include PHPExcel */
		$report_gen_by = "Prism Alerts";
		$report_gen_on = date("M j, Y");
		$excelData=array("title"=>$title, "file_name"=>$file_name, "excel_data"=>$data,"footer"=>$footer,"rpt_gen_by"=>$report_gen_by,"rpt_gen_on"=>$report_gen_on);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("GoDB Inc.");
		

			if(isset($excelData))
			{
				$cur_excel_data = $excelData;
				
				session_write_close();
				
				
				$download_filename = $this->clean_xls_filename($cur_excel_data['title']);
				if(isset($cur_excel_data['file_name'])) {
					$download_filename = str_replace(' ', '_', trim($cur_excel_data['file_name']));
				}
				
				$hideColumns = "";
				if(isset($excel_content['hiddenColumns'])) {
					$hideColumns = $excel_content['hiddenColumns'];
				}
				
				$excel_report=$cur_excel_data['excel_data']['excel_report'];
				
				$columnsArray=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ");
				
				
				$alignCenter = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);

				$pageHeaderStyle=array(
								'fill' => array(
												'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
												'color'	=> array('rgb' => '333399'),
											),
								'font' => array(
												'bold'  => true,
												'color' => array('rgb' => 'FFFFFF'),
												'size'  => 9,
											),
							);
						
				
				$contentHeaderStyle=array(
								'fill' => array(
												'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
												'color'	=> array('rgb' => 'CCCCFF'),
											),
								'font' => array(
												'bold'  => true,
												'color' => array('rgb' => '000000'),
												'size'  => 9,
											),
								'borders' => array(
											'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
											'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
											'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
											'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
											),
								'alignment' => array(
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
											)
							);
							
				$contentFooterStyle=array(
								'fill' => array(
												'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
												'color'	=> array('rgb' => 'CCCCFF'),
											),
								'font' => array(
												'bold'  => true,
												'color' => array('rgb' => '000000'),
												'size'  => 9,
											)
							);
				
				$font_color_red = array('font'  => array(
										'bold'  => false,
										'color' => array('rgb' => 'FF0000')
									));
				
				$font_color_green = array('font'  => array(
										'bold'  => false,
										'color' => array('rgb' => '00FF00')
									));
				
				$font_color_dgreen = array('font'  => array(
										'bold'  => false,
										'color' => array('rgb' => '03570A')
									));
									
				$font_color_black = array('font'  => array(
										'bold'  => false,
										'color' => array('rgb' => '000000')
									));
				
				$bold = array('font'  => array(
										'bold'  => true,
										'color' => array('rgb' => '000000'),
										'size'  => 12
									));
				
				$bold_center = array('font'  => array(
										'bold'  => true,
										'color' => array('rgb' => '000000'),
										'size'  => 12
									),
									'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
									));
				
				$normal_center = array('font'  => array(
										'bold'  => false,
										'color' => array('rgb' => '000000'),
										'size'  => 12
									),
									'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
									));
				
				$normal = array('font'  => array(
										'bold'  => false,
										'color' => array('rgb' => '000000'),
										'size'  => 12
									));
				
				$borders = array(
					  'borders' => array(
						  'allborders' => array(
							  'style' => PHPExcel_Style_Border::BORDER_THIN
						  )
					  )
				  );
				error_reporting(E_ALL);
				ini_set('display_errors',1);
				
				// Excel Content 
				$uniq_ytd=$cur_excel_data['excel_data']['uniq_ytd'];
				$uniq_wtd=$cur_excel_data['excel_data']['uniq_wtd'];
				$uniq_daily=$cur_excel_data['excel_data']['uniq_daily'];
				
				$muniq_ytd=$cur_excel_data['excel_data']['muniq_ytd'];
				$muniq_wtd=$cur_excel_data['excel_data']['muniq_wtd'];
				$muniq_daily=$cur_excel_data['excel_data']['muniq_daily'];
				
				$avg_ytd=$cur_excel_data['excel_data']['avg_ytd'];
				$avg_wtd=$cur_excel_data['excel_data']['avg_wtd'];
				$avg_daily=$cur_excel_data['excel_data']['avg_daily'];
				
				$worksheet = $objPHPExcel->getActiveSheet();
				$objPHPExcel->getActiveSheet()->setTitle('Summary');
				//$objPHPExcel->setActiveSheetIndex(0);
				$worksheet->setCellValue('A1', 'Prism Outlook Installs');
				$worksheet->setCellValue('B1', $cur_excel_data['excel_data']['tot_prism']['installs']);
				$worksheet->setCellValue('A2', 'Mobile App Users');
				$worksheet->setCellValue('B2', (count($cur_excel_data['excel_data']['excel_report'][1]['data'])-2) );
				$worksheet->getStyle('A1:A2')->applyFromArray($bold);
				$worksheet->getStyle('B1:B2')->applyFromArray($bold);
				$worksheet->getStyle('A1:B2')->applyFromArray($borders);
				$worksheet->getColumnDimension("A")->setWidth(40);
				$worksheet->getColumnDimension("C")->setWidth(15);
				$worksheet->getColumnDimension("D")->setWidth(15);
				
				$worksheet->setCellValue('A4', 'Daily Average of Users');
				$worksheet->mergeCells('A4:D4');
				$worksheet->setCellValue('B5', 'Daily');
				$worksheet->setCellValue('C5', 'WTD (Average)');
				$worksheet->setCellValue('D5', 'YTD(Average)');
				$worksheet->setCellValue('A6', 'Users using Mail Panel Search');
				$worksheet->setCellValue('A7', 'Users Using the Dashboard');
				$worksheet->setCellValue('A8', 'Users Using SF Tasks');
				
				$worksheet->setCellValue('B6', $avg_daily['search']);
				$worksheet->setCellValue('C6', $avg_wtd['search']);
				$worksheet->setCellValue('D6', $avg_ytd['search']);
				
				$worksheet->setCellValue('B7', $avg_daily['dashboardlaunch']);
				$worksheet->setCellValue('C7', $avg_wtd['dashboardlaunch']);
				$worksheet->setCellValue('D7', $avg_ytd['dashboardlaunch']);
				
				$worksheet->setCellValue('B8', $avg_daily['sf_ops']);
				$worksheet->setCellValue('C8', $avg_wtd['sf_ops']);
				$worksheet->setCellValue('D8', $avg_ytd['sf_ops']);
				
				$worksheet->getStyle('A4:D8')->applyFromArray($borders);
				$worksheet->getStyle('A5:D5')->applyFromArray($normal_center);
				$worksheet->getStyle('A6:D8')->applyFromArray($normal);
				$worksheet->getStyle('A4:D4')->applyFromArray($bold_center);
				
				$worksheet->setCellValue('A10', 'Unique User Counts');
				$worksheet->mergeCells('A10:D10');
				$worksheet->setCellValue('B11', 'Daily');
				$worksheet->setCellValue('C11', 'WTD');
				$worksheet->setCellValue('D11', 'YTD');
				$worksheet->setCellValue('A12', 'Users using Mail Panel Search');
				$worksheet->setCellValue('A13', 'Users Using the Dashboard');
				$worksheet->setCellValue('A14', 'Users using SF tasks(Add/Edit contact/task)');
				
				$worksheet->setCellValue('B12', $uniq_daily['search_cnt']);
				$worksheet->setCellValue('C12', $uniq_wtd['search_cnt']);
				$worksheet->setCellValue('D12', $uniq_ytd['search_cnt']);
				
				$worksheet->setCellValue('B13', $uniq_daily['dashboardlaunch_cnt']);
				$worksheet->setCellValue('C13', $uniq_wtd['dashboardlaunch_cnt']);
				$worksheet->setCellValue('D13', $uniq_ytd['dashboardlaunch_cnt']);
				
				$worksheet->setCellValue('B14', $uniq_daily['sf_ops_cnt']);
				$worksheet->setCellValue('C14', $uniq_wtd['sf_ops_cnt']);
				$worksheet->setCellValue('D14', $uniq_ytd['sf_ops_cnt']);
				
				$worksheet->getStyle('A10:D14')->applyFromArray($borders);
				$worksheet->getStyle('A11:D11')->applyFromArray($normal_center);
				$worksheet->getStyle('A12:D14')->applyFromArray($normal);
				$worksheet->getStyle('A10:D10')->applyFromArray($bold_center);
				
				$worksheet->setCellValue('A16', 'Unique User Counts for Mobile');
				$worksheet->mergeCells('A16:D16');
				$worksheet->setCellValue('B17', 'Daily');
				$worksheet->setCellValue('C17', 'WTD');
				$worksheet->setCellValue('D17', 'YTD');
				$worksheet->setCellValue('A18', 'Users using the AppLanch');
				$worksheet->setCellValue('A19', 'Users using SF tasks(Add/Edit contact/task)');
				
				$worksheet->setCellValue('B18', $muniq_daily['applaunch_cnt']);
				$worksheet->setCellValue('C18', $muniq_wtd['applaunch_cnt']);
				$worksheet->setCellValue('D18', $muniq_ytd['applaunch_cnt']);
				
				$worksheet->setCellValue('B19', $muniq_daily['sf_ops_cnt']);
				$worksheet->setCellValue('C19', $muniq_wtd['sf_ops_cnt']);
				$worksheet->setCellValue('D19', $muniq_ytd['sf_ops_cnt']);
				
				$worksheet->getStyle('A16:D19')->applyFromArray($borders);
				$worksheet->getStyle('A17:D17')->applyFromArray($normal_center);
				$worksheet->getStyle('A18:D19')->applyFromArray($normal);
				$worksheet->getStyle('A16:D16')->applyFromArray($bold_center);
				
				$worksheet->setCellValue('A21', "No. of Users not used system in this month :".(count($cur_excel_data['excel_data']['excel_report'][7]['data'])-2));
				$worksheet->mergeCells('A21:B21');
				
				/***Average Moving Chart******/
				
				
				$mov_cnt = count($cur_excel_data['excel_data']['excel_report'][8]['data']);
				$moving_cnt = $mov_cnt-2;
				$m_end_cnt = $mov_cnt-1;
				$m_start_cnt = $mov_cnt-92;
				
				$dsl=array(
						new \PHPExcel_Chart_DataSeriesValues('String', '7days_Moving_Average!$B$1', NULL, 1),
						new \PHPExcel_Chart_DataSeriesValues('String', '7days_Moving_Average!$C$1', NULL, 1),
						new \PHPExcel_Chart_DataSeriesValues('String', '7days_Moving_Average!$D$1', NULL, 1)
					);
					
				$xal=array(
						new \PHPExcel_Chart_DataSeriesValues('String', '7days_Moving_Average!$A$'.$m_start_cnt.':$A$'.$m_end_cnt, NULL, $moving_cnt),
					);
				
				$dsv=array(
						new \PHPExcel_Chart_DataSeriesValues('Number', '7days_Moving_Average!$B$'.$m_start_cnt.':$B$'.$m_end_cnt, NULL, $moving_cnt),
						new \PHPExcel_Chart_DataSeriesValues('Number', '7days_Moving_Average!$C$'.$m_start_cnt.':$C$'.$m_end_cnt, NULL, $moving_cnt),
						new \PHPExcel_Chart_DataSeriesValues('Number', '7days_Moving_Average!$D$'.$m_start_cnt.':$D$'.$m_end_cnt, NULL, $moving_cnt)
					);
				
				$data_obj = new \PHPExcel_Chart_DataSeriesValues();
				$data_obj->setPointMarker('none');
				
				$ds=new \PHPExcel_Chart_DataSeries(
							\PHPExcel_Chart_DataSeries::TYPE_LINECHART,
							\PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
							range(0, count($dsv)-1),
							$dsl,
							$xal,
							$dsv
							);
				//$seriesPlot = new LinePlot();
				
				$pa=new \PHPExcel_Chart_PlotArea(NULL, array($ds));
				$legend=new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);
				
				$title=new \PHPExcel_Chart_Title('7 Days Moving Average');
				
				$chart= new \PHPExcel_Chart(
							'chart1',
							$title,
							$legend,
							$pa,
							true,
							0,
							NULL, 
							NULL
							);

				$chart->setTopLeftPosition('A25');
				$chart->setBottomRightPosition('H43');
				//$chart->setPointMarker(null);
				$worksheet->addChart($chart);
				
				//Daily Count Chart
				
				$daily_cnt = count($cur_excel_data['excel_data']['excel_report'][0]['data']);
				$d_cnt = $daily_cnt-2;
				$d_end_cnt = $daily_cnt-1;
				$d_start_cnt = $daily_cnt-66;
				
				$dsl_2=array(
						new \PHPExcel_Chart_DataSeriesValues('String', 'Daily_Count!$B$1', NULL, 1),
						new \PHPExcel_Chart_DataSeriesValues('String', 'Daily_Count!$C$1', NULL, 1),
						new \PHPExcel_Chart_DataSeriesValues('String', 'Daily_Count!$D$1', NULL, 1)
						,new \PHPExcel_Chart_DataSeriesValues('String', 'Daily_Count!$E$1', NULL, 1)
					);
					
				$xal_2=array(
						new \PHPExcel_Chart_DataSeriesValues('String', 'Daily_Count!$A$'.$d_start_cnt.':$A$'.$d_end_cnt, NULL, $d_cnt),
					);
				
				$dsv_2=array(
						new \PHPExcel_Chart_DataSeriesValues('Number', 'Daily_Count!$B$'.$d_start_cnt.':$B$'.$d_end_cnt, NULL, $d_cnt),
						new \PHPExcel_Chart_DataSeriesValues('Number', 'Daily_Count!$C$'.$d_start_cnt.':$C$'.$d_end_cnt, NULL, $d_cnt),
						new \PHPExcel_Chart_DataSeriesValues('Number', 'Daily_Count!$D$'.$d_start_cnt.':$D$'.$d_end_cnt, NULL, $d_cnt)
						,new \PHPExcel_Chart_DataSeriesValues('Number', 'Daily_Count!$E$'.$d_start_cnt.':$E$'.$d_end_cnt, NULL, $d_cnt)
					);
				
				$ds_2=new \PHPExcel_Chart_DataSeries(
							\PHPExcel_Chart_DataSeries::TYPE_LINECHART,
							\PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
							range(0, count($dsv_2)-1),
							$dsl_2,
							$xal_2,
							$dsv_2
							);
				
				$pa_2=new \PHPExcel_Chart_PlotArea(NULL, array($ds_2));
				$legend_2=new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);
				
				$title_2=new \PHPExcel_Chart_Title('Daily Count');
				
				$chart_2= new \PHPExcel_Chart(
							'chart2',
							$title_2,
							$legend_2,
							$pa_2,
							true,
							0,
							NULL, 
							NULL
							);

				$chart_2->setTopLeftPosition('A046');
				$chart_2->setBottomRightPosition('H64');
				$worksheet->addChart($chart_2);
				
				/*********************************/
				
				
				
				for($e=0;$e<count($excel_report);$e++) 
				{
					
					//$objPHPExcel->setActiveSheetIndex();
					//$objPHPExcel->getActiveSheet()->setTitle($excel_report[$e]["title"]);
				
					$worksheet = $objPHPExcel->createSheet($e+1);
					//$objPHPExcel->setActiveSheetIndex($e+1);
					$worksheet->setTitle($excel_report[$e]["title"]);
					
					$excel_content = $excel_report[$e]["data"];
					
					$headerCont = reset($excel_content);
					$headerColumnCount = count($headerCont);
					
					$xlsDataType = end($excel_content);
					
					$lastColCellNo="A";
					
					$rowNumber=1;
					$startRowNumber=$rowNumber;
					
					$headerRowNumber=$rowNumber;
					
					$excel_content_count = count($excel_content)-1;
					$excel_content_footer_row = count($excel_content)-2;
					
					$content_length_arr = array();
					$content_length_arr = array_fill(0,$headerColumnCount,1);
					$content_length_arr[$headerColumnCount-1] = 24;
				
					for($row=0;$row<$excel_content_count;$row++)
					{
						for($col=0;$col<$headerColumnCount;$col++)
						{
							$curCellNo=$columnsArray[$col].$rowNumber;
							$curCellValue=trim(strip_tags($excel_content[$row][$col]));
							
							if($row!=0)
							{
								if($xlsDataType[$col]=="SNO")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
								}
								else if($xlsDataType[$col]=="NUMBER")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
								}
								elseif($xlsDataType[$col]=="MONEY")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode("[Black]$#,##0;[Red]($#,##0)");
									
								}
								elseif($xlsDataType[$col]=="MONEYDECIMAL")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
									//$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode("[Black]$#,##0;[Red]($#,##0)");
									
								}
								else if($xlsDataType[$col]=="PERCENTAGE")
								{
									$worksheet->setCellValue($curCellNo, ($curCellValue/100));

									$cellFontColorArray = array();
									if($curCellValue>0)
									{
										$cellFontColorArray = $font_color_dgreen;
									}
									else if($curCellValue==0)
									{
										$cellFontColorArray = $font_color_black;
									}else{
										$cellFontColorArray = $font_color_red;
									}
										
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
									$worksheet->getStyle($curCellNo)->applyFromArray($cellFontColorArray);
									//$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode("[Green]##0.#0%;[Red]-###.#0%");
								}
								else if($xlsDataType[$col]=="PERCENTAGEDECIMAL")
								{
									$worksheet->setCellValue($curCellNo, ($curCellValue/100));

									$cellFontColorArray = array();
									if($curCellValue>0)
									{
										$cellFontColorArray = $font_color_dgreen;
									}
									else if($curCellValue==0)
									{
										$cellFontColorArray = $font_color_black;
									}else{
										$cellFontColorArray = $font_color_red;
									}
										
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
									$worksheet->getStyle($curCellNo)->applyFromArray($cellFontColorArray);						
								}
								else if($xlsDataType[$col]=="STRING")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
								}
								else if($xlsDataType[$col]=="DATETIME")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
								}
								else if($xlsDataType[$col]=="TEXT")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
								}
								else if($xlsDataType[$col]=="PROFITLOSS")
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode("[Green]##0;[Red]-###");
								}
								else
								{
									$worksheet->setCellValue($curCellNo, $curCellValue);
									$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
								}
							}
							else
							{
								$worksheet->setCellValue($curCellNo, $curCellValue);
								$worksheet->getStyle($curCellNo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
							}
							
							$currentCellValueLength = strlen($curCellValue) + 2;
							if($content_length_arr[$col]<$currentCellValueLength)
							{
								$content_length_arr[$col] = $currentCellValueLength;
							}
							
							//$worksheet->getColumnDimension($columnsArray[$col])->setAutoSize(true);
							$lastColCellNo=$columnsArray[$col];
						}
						$rowNumber++;
					}
				

					$headerRowHighlight="A".$headerRowNumber.":".$lastColCellNo.$headerRowNumber;
					$worksheet->getStyle($headerRowHighlight)->applyFromArray($contentHeaderStyle);
					
					if(isset($cur_excel_data['footer'])) {
						$footerRowHighlight="A".($rowNumber-1).":".$lastColCellNo.($rowNumber-1);
						$worksheet->getStyle($footerRowHighlight)->applyFromArray($contentFooterStyle);
					}
					/*PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

					foreach(range('A',$lastColCellNo) as $columnID) {
							$worksheet->getColumnDimension($columnID)->setAutoSize(true);
					}*/
					
					$i=0;
					foreach(range('A',$lastColCellNo) as $columnID) {
						$worksheet->getColumnDimension($columnID)->setWidth($content_length_arr[$i]);
						$i++;
					}
					
					if($hideColumns != "") {
						foreach($hideColumns as $val) {
							if($val != "")
								$worksheet->getColumnDimension($val)->setVisible(false);
						}
					}
				}
				$objPHPExcel->setActiveSheetIndex(0);
				$path = ROOT_PATH."assets/userstats/";
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->setIncludeCharts(true);
				ob_clean();
				$objWriter->save(str_replace('.php', '.xls',$path.$download_filename.'.xls'));
				//$objWriter->save($path.$download_filename.'.xlsx');

				$filename=$download_filename.'.xls';
				$file = $path.$filename;
				chmod($file, 0777);	
				sleep(3);

				$message =	"<table style='width:1000px'>
								<tr><td>Hi,<br><br>Attached the Usage Stats for <b>".date('dS M Y')."</b>.</td></tr>
								<tr><td><br><b>
									<table border='1' style='border-collapse: collapse;'>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Prism Outlook Installed</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>".$cur_excel_data['excel_data']['tot_prism']['installs']."</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Mobile App Users</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". (count($cur_excel_data['excel_data']['excel_report'][1]['data'])-2) ."</td>
										</tr>
									</table></b>
								</td></tr>
								<tr><td><br>
									<table border='1' style='border-collapse: collapse;'>
										<tr><td colspan='4' style='height:20px;text-align:center;'><b>Daily Average of Users</b></td></tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>&nbsp;</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>Daily</td>
											<td style='width:90px;padding-left:10px;text-align:center;'>WTD(Average)</td>
											<td style='width:90px;padding-left:10px;text-align:center;'>YTD(Average)</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users using Mail Panel Search</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_daily['search'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_wtd['search'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_ytd['search'] ."</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users Using the Dashboard</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_daily['dashboardlaunch'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_wtd['dashboardlaunch'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_ytd['dashboardlaunch'] ."</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users Using SF Tasks</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_daily['sf_ops'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_wtd['sf_ops'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $avg_ytd['sf_ops'] ."</td>
										</tr>
									</table>
								</td></tr>
								<tr><td><br>
									<table border='1' style='border-collapse: collapse;'>
										<tr><td colspan='4' style='height:20px;text-align:center;'><b>Unique User Counts</b></td></tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>&nbsp;</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>Daily</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>WTD</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>YTD</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users using Mail Panel Search</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_daily['search_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_wtd['search_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_ytd['search_cnt'] ."</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users Using the Dashboard</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_daily['dashboardlaunch_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_wtd['dashboardlaunch_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_ytd['dashboardlaunch_cnt'] ."</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users using SF tasks(Add/Edit contact/task)</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_daily['sf_ops_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_wtd['sf_ops_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $uniq_ytd['sf_ops_cnt'] ."</td>
										</tr>
									</table>
								</td></tr>
								<tr><td><br>
									<table border='1' style='border-collapse: collapse;'>
										<tr><td colspan='4' style='height:20px;text-align:center;'><b>Unique User Counts for Mobile</b></td></tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>&nbsp;</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>Daily</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>WTD</td>
											<td style='width:50px;padding-left:10px;text-align:center;'>YTD</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users using the AppLanch</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $muniq_daily['applaunch_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $muniq_wtd['applaunch_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $muniq_ytd['applaunch_cnt'] ."</td>
										</tr>
										<tr>
											<td style='width:300px;height:20px;padding-left:7px;'>Users using SF tasks(Add/Edit contact/task)</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $muniq_daily['sf_ops_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $muniq_wtd['sf_ops_cnt'] ."</td>
											<td style='width:50px;padding-left:10px;text-align:right;'>". $muniq_ytd['sf_ops_cnt'] ."</td>
										</tr>
									</table>
								</td></tr>
								<tr><td><br>No. of Users not used system in this month : ". (count($cur_excel_data['excel_data']['excel_report'][7]['data'])-2) ."</td></tr>
							</table>";

				
				
				//function mail_attachment ($from , $to, $subject, $message, $attachment){
				$to="mkarthikeyan@godbtech.com";
				$from="vivian@godbtech.com";
				$subject = "Prism User Stats - ".date('d-m-Y');
				//$message = "Today Prism user stats";
				$attachment=$file;
				$fileatt = $attachment; // Path to the file                  
				$fileatt_type = 'application/octet-stream'; // File Type 
				$start= strrpos($attachment, '/') == -1 ? strrpos($attachment, '//') : strrpos($attachment, '/')+1;
				$fileatt_name = substr($attachment, $start, strlen($attachment)); // Filename that will be used for the file as the     attachment 

				$email_from = $from; // Who the email is from 
				$email_subject =  $subject; // The Subject of the email 
				$email_txt = $message; // Message that the email has in it 
				ini_set("SMTP", "smtp.cowen.corp");
				ini_set("sendmail_from", "Cowen Support <vivian@godbtech.com>");

				$email_to = $to; // Who the email is to

				$headers = "From: Cowen Support <vivian@godbtech.com>";//.$email_from;

				$file = fopen($fileatt,'rb'); 
				$data = fread($file,filesize($fileatt)); 
				fclose($file); 

				$semi_rand = md5(time()); 
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

				$headers .= "\nwebsiteaddress-Version: 1.0\n" . 
						"Content-Type: multipart/mixed;\n" . 
						" boundary=\"{$mime_boundary}\""; 

				$email_message = "This is a multi-part message in websiteaddress format.\n\n" . 
							"--{$mime_boundary}\n" . 
							"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
						   "Content-Transfer-Encoding: 7bit\n\n".$email_txt. "\n\n"; 
				$data = chunk_split(base64_encode($data)); 
				$email_message .= "--{$mime_boundary}\n" . 
							  "Content-Type: {$fileatt_type};\n" . 
							  " name=\"{$fileatt_name}\"\n" . 
							  "Content-Transfer-Encoding: base64\n\n" . 
							 $data . "\n\n" . 
							  "--{$mime_boundary}--\n"; 


				$ok = mail($email_to, $email_subject, $email_message, $headers); 

				if($ok) { 
				//echo "Attachment has been mailed !";
				echo json_encode(array("status"=>0,"msg"=>"success"));
				} else { 
				echo json_encode(array("status"=>-1,"msg"=>"Failed"));
					//die("Sorry but the email could not be sent. Please go back and try again!"); 
				} 
				//}	
				
				exit;
				
				
			}
			else{
				echo "ExcelData Not Available!!!";
			}

	}
	
	function clean_xls_filename($string) {
		$string = str_replace(' ', '-', trim($string)); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

	

}
?>