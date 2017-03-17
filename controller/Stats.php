<?php 
class controlStats extends modelStats
{
	public function __construct()
	{
		parent::__construct();
		$this->sessionid = time();
		$this->cowen_support = COWEN_SUPPORT_EMAIL; 
	}
	
	public function UserStats()
	{
		$header=array(
				"Name",
				"Role",
				"Searches",
				"DashboardLaunch",
				"SF_ops",
				"Last_dashboard_usage",
				"Last_sf_hit"
			);
		
		$data = array();
		
		/******* YTD for Dashboard ****/
		
		$ytd_dash = array();
			
		$ytc_query="select name, role, search,dashboardlaunch,sf_ops,last_dashboard_usage,last_sf_hit from T_STATS_YTD";
		$ytc_result = $this->rset($ytc_query);
		
		array_push($ytd_dash,$header);
		foreach($ytc_result as $ytc_row) {
			array_push($ytd_dash, array(
				$ytc_row['name']
				,$ytc_row['role']
				,$ytc_row['search']
				,$ytc_row['dashboardlaunch']
				,$ytc_row['sf_ops']
				,$ytc_row['last_dashboard_usage']
				,$ytc_row['last_sf_hit']
			));
		}
		array_push($ytd_dash,array("STRING","STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['ytd_dash']= $ytd_dash;
		
		/******** WTD for Dashboarc **********/
		
		$wtd_dash = array();
			
		$wtd_query="select name, role, search,dashboardlaunch,sf_ops,last_dashboard_usage,last_sf_hit from T_STATS_WTD";
		$wtd_result = $this->rset($wtd_query);
		
		array_push($wtd_dash,$header);
		foreach($wtd_result as $wtd_row) {
			array_push($wtd_dash, array(
				$wtd_row['name']
				,$wtd_row['role']
				,$wtd_row['search']
				,$wtd_row['dashboardlaunch']
				,$wtd_row['sf_ops']
				,$wtd_row['last_dashboard_usage']
				,$wtd_row['last_sf_hit']
			));
		}
		array_push($wtd_dash,array("STRING","STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['wtd_dash']= $wtd_dash;
		
		/*********** Daily for Dashboard ******/
		
		$daily_dash = array();
		
		$daily_query="select name, role, search,dashboardlaunch,sf_ops,last_dashboard_usage,last_sf_hit from T_STATS_DAILY";
		$daily_result = $this->rset($daily_query);
		
		array_push($daily_dash,$header);
		foreach($daily_result as $daily_row) {
			array_push($daily_dash, array(
				$daily_row['name']
				,$daily_row['role']
				,$daily_row['search']
				,$daily_row['dashboardlaunch']
				,$daily_row['sf_ops']
				,$daily_row['last_dashboard_usage']
				,$daily_row['last_sf_hit']
			));
		}
		array_push($daily_dash,array("STRING","STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['daily_dash']= $daily_dash;
		
		/*********** DailyCount for Dashboard ******/
		
		$dc_dash = array();
		$s_date=date('Y-01-01 00:00:00');
		$day=date('w');
		if($day==1)
			$e_date=date('Y-m-d 23:59:59',strtotime("-3 days"));
		else
			$e_date=date('Y-m-d 23:59:59',strtotime("-1 day"));
		
		$dc_header=array(
				"Log Date",
				"Searches",
				"DashboardLaunch",
				"SF_ops",
				"Unique_User"
			);
		$dc_query="select log_date,search,dashboardlaunch,sf_ops,unique_user from T_STATS_DAILY_COUNT";
		
		$dc_query_2="select count(distinct user_ID ) as installs 
		from api_access_log L
		where 
			  user_id not in ('balaramans','kaliyamurthym','easwarann','sureshr','jayakaranv','bhayania','murugeshs','karthikeyans',' jsolomon',' BrightfJ',' learyr')
			  and isnull(prism_source,'web') <> 'mobile'
		and ip_address not in (select distinct ip_address from api_access_log where user_id in ('balaramans','kaliyamurthym','easwarann','sureshr','jayakaranv','bhayania','murugeshs','karthikeyans',' jsolomon',' BrightfJ',' learyr'))
		and log_date >= '$s_date' and log_date <= '$e_date'";				
		
		$dc_result = $this->rset($dc_query);
		$dc_result2 = $this->rset($dc_query_2);
		
		array_push($dc_dash,$dc_header);
		foreach($dc_result as $dc_row) {
			array_push($dc_dash, array(
					$dc_row['log_date']
					,$dc_row['search']
					,$dc_row['dashboardlaunch']
					,$dc_row['sf_ops']
					,$dc_row['unique_user']
				));
		}
		array_push($dc_dash,array("STRING","STRING","STRING","STRING","STRING"));
		
		//$data['dc_dash']= $dc_dash;
		$data['tot_prism']=$dc_result2[0];
		
		
		/*********** YTD for Mobile ******/
		
		$ytd_mobile = array();
		
		$mheader=array("Name","Role","AppLaunch","SF_ops","Last_App_usage","Last_sf_hit");
		
		$mytd_query="select name,role,applaunch,sf_ops,last_app_usage,last_sf_hit from T_STATS_YTD_MOBILE";
		$mytd_result = $this->rset($mytd_query);
		
		array_push($ytd_mobile,$mheader);
		foreach($mytd_result as $mytd_row) {
			array_push($ytd_mobile, array(
				$mytd_row['name']
				,$mytd_row['role']
				,$mytd_row['applaunch']
				,$mytd_row['sf_ops']
				,$mytd_row['last_app_usage']
				,$mytd_row['last_sf_hit']
			));
		}
		array_push($ytd_mobile,array("STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['ytd_mobile']= $ytd_mobile;
		
		/*********** WTD for Mobile ******/
		
		$wtd_mobile = array();
		
		$mwtd_query="select name,role,applaunch,sf_ops,last_app_usage,last_sf_hit from T_STATS_WTD_MOBILE";				
		
		$mwtd_result = $this->rset($mwtd_query);
		
		array_push($wtd_mobile,$mheader);
		foreach($mwtd_result as $mwtd_row) {
			array_push($wtd_mobile, array(
				$mwtd_row['name']
				,$mwtd_row['role']
				,$mwtd_row['applaunch']
				,$mwtd_row['sf_ops']
				,$mwtd_row['last_app_usage']
				,$mwtd_row['last_sf_hit']
			));
		}
		array_push($wtd_mobile,array("STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['wtd_mobile']= $wtd_mobile;
		
		/*********** Daily for Mobile ******/
		
		$daily_mobile = array();
		$day=date('w');
		if($day==1) {
			$s_date=date('Y-m-d 00:00:00',strtotime("-3 days"));
			$e_date=date('Y-m-d 00:00:00',strtotime("-2 days"));
		}
		else {
			$s_date=date('Y-m-d 00:00:00',strtotime("-1 day"));
			$e_date=date('Y-m-d 00:00:00');
		}
		
		$mdaily_query="select name,role,applaunch,sf_ops,last_app_usage,last_sf_hit from T_STATS_DAILY_MOBILE";				
		
		$mdaily_result = $this->rset($mdaily_query);
		
		array_push($daily_mobile,$mheader);
		foreach($mdaily_result as $mdaily_row) {
			array_push($daily_mobile, array(
				$mdaily_row['name']
				,$mdaily_row['role']
				,$mdaily_row['applaunch']
				,$mdaily_row['sf_ops']
				,$mdaily_row['last_app_usage']
				,$mdaily_row['last_sf_hit']
			));
		}
		array_push($daily_mobile,array("STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['daily_mobile']= $daily_mobile;
		
		/*********** No. of users not used Prism ******/
		
		$not_used = array();
			
		$not_used_query="select name,role,search,dashboardlaunch,sf_ops,last_dashboard_usage,last_sf_hit from T_STATS_NOTUSED_USERS";
		$not_used_result = $this->rset($not_used_query);
		
		array_push($not_used,$header);
		foreach($not_used_result as $not_used_row) {
			array_push($not_used, array(
				$not_used_row['name']
				,$not_used_row['role']
				,$not_used_row['search']
				,$not_used_row['dashboardlaunch']
				,$not_used_row['sf_ops']
				,$not_used_row['last_dashboard_usage']
				,$not_used_row['last_sf_hit']
			));
		}
		array_push($not_used,array("STRING","STRING","STRING","STRING","STRING","STRING","STRING"));
		
		//$data['not_used']= $not_used;
		
		
		/******** 7 DAY MOVING AVERAGE STARTS ***********/
		
		$sql_moving_avg="select date,searches,DashboardLaunch,SF_ops from D_MOVING_USAGE";
		$result_moving_avg=$this->rset($sql_moving_avg);
		
		$moving_avg = array();
		$ma_header=array(
						"date",
						"searches",
						"DashboardLaunch",
						"SF_ops"
					);
		
		array_push($moving_avg,$ma_header);
		foreach($result_moving_avg as $ma_row) {
			array_push($moving_avg, array(
				$ma_row['date']
				,$ma_row['searches']
				,$ma_row['DashboardLaunch']
				,$ma_row['SF_ops']
			));
		}
		array_push($moving_avg,array("STRING","STRING","STRING","STRING"));
		
		//$data['moving_avg']= $moving_avg;
		
		/******** Email status ***********/
		
		$sql_emails_sent="select log_date,emails_sent,users_used from T_STATS_EMAIL_SENT";
		$result_emails_sent=$this->rset($sql_emails_sent);
		
		$emails_sent = array();
		$es_header=array(
						"Log date",
						"Emails Sent",
						"Users Used"
					);
		
		array_push($emails_sent,$es_header);
		foreach($result_emails_sent as $es_row) {
			array_push($emails_sent, array(
				$es_row['log_date']
				,$es_row['emails_sent']
				,$es_row['users_used']
			));
		}
		array_push($emails_sent,array("STRING","STRING","STRING"));
		
		//$data['moving_avg']= $moving_avg;
		
		/******** Call status ***********/
		
		$sql_call_status="select log_date,total_calls,users_used from T_STATS_CALL_STATUS";
		$result_call_status=$this->rset($sql_call_status);
		
		$call_status = array();
		$cs_header=array(
						"Log date",
						"Total Calls",
						"Users Used"
					);
		
		array_push($call_status,$cs_header);
		foreach($result_call_status as $cs_row) {
			array_push($call_status, array(
				$cs_row['log_date']
				,$cs_row['total_calls']
				,$cs_row['users_used']
			));
		}
		array_push($call_status,array("STRING","STRING","STRING"));
		
		//$data['moving_avg']= $moving_avg;
		
		/****************Summary**********************/
		
		$sql_uniq_ytd="select SUM(case when search >0  then 1 else 0 end) as search_cnt,SUM(case when dashboardlaunch >0  then 1 else 0 end) as dashboardlaunch_cnt,SUM(case when sf_ops >0  then 1 else 0 end) as sf_ops_cnt from T_STATS_YTD";
		$sql_uniq_wtd="select SUM(case when search >0  then 1 else 0 end) as search_cnt,SUM(case when dashboardlaunch >0  then 1 else 0 end) as dashboardlaunch_cnt,SUM(case when sf_ops >0  then 1 else 0 end) as sf_ops_cnt from T_STATS_WTD";
		$sql_uniq_daily="select SUM(case when search >0  then 1 else 0 end) as search_cnt,SUM(case when dashboardlaunch >0  then 1 else 0 end) as dashboardlaunch_cnt,SUM(case when sf_ops >0  then 1 else 0 end) as sf_ops_cnt from T_STATS_DAILY";
		
		$sql_muniq_ytd="select SUM(case when applaunch >0  then 1 else 0 end) as applaunch_cnt,SUM(case when sf_ops >0  then 1 else 0 end) as sf_ops_cnt from T_STATS_YTD_MOBILE";
		$sql_muniq_wtd="select SUM(case when applaunch >0  then 1 else 0 end) as applaunch_cnt,SUM(case when sf_ops >0  then 1 else 0 end) as sf_ops_cnt from T_STATS_WTD_MOBILE";
		$sql_muniq_daily="select SUM(case when applaunch >0  then 1 else 0 end) as applaunch_cnt,SUM(case when sf_ops >0  then 1 else 0 end) as sf_ops_cnt from T_STATS_DAILY_MOBILE";
		
		$sql_avg_ytd="select AVG(search) as search,AVG(dashboardlaunch) as dashboardlaunch,AVG(sf_ops) as sf_ops from T_STATS_DAILY_COUNT";
		
		$day = date('w');

		if($day==1){
			$day=$day+6;
			$s_date = date('Y-m-d',strtotime('-'.$day.' days'));
		}
		else {
			$day=$day-1;
			$s_date = date('Y-m-d',strtotime('-'.$day.' days'));
		}

		$sql_avg_wtd="select AVG(search) as search,AVG(dashboardlaunch) as dashboardlaunch,AVG(sf_ops) as sf_ops from T_STATS_DAILY_COUNT where DATEADD(wk, DATEDIFF(wk, 0,cast(log_date as date)),0)>=cast('$s_date' as datetime)";

		$sql_avg_daily="select TOP 1 search, dashboardlaunch,sf_ops from T_STATS_DAILY_COUNT order by log_date desc";

		
		$res_uniq_ytd=$this->rset($sql_uniq_ytd);
		$res_uniq_wtd=$this->rset($sql_uniq_wtd);
		$res_uniq_daily=$this->rset($sql_uniq_daily);
		
		$res_muniq_ytd=$this->rset($sql_muniq_ytd);
		$res_muniq_wtd=$this->rset($sql_muniq_wtd);
		$res_muniq_daily=$this->rset($sql_muniq_daily);
		
		$res_avg_ytd=$this->rset($sql_avg_ytd);
		$res_avg_wtd=$this->rset($sql_avg_wtd);
		$res_avg_daily=$this->rset($sql_avg_daily);
		
		$data['uniq_ytd']=$res_uniq_ytd[0];
		$data['uniq_wtd']=$res_uniq_wtd[0];
		$data['uniq_daily']=$res_uniq_daily[0];
		
		$data['muniq_ytd']=$res_muniq_ytd[0];
		$data['muniq_wtd']=$res_muniq_wtd[0];
		$data['muniq_daily']=$res_muniq_daily[0];
		
		$data['avg_ytd']=$res_avg_ytd[0];
		$data['avg_wtd']=$res_avg_wtd[0];
		$data['avg_daily']=$res_avg_daily[0];
		
		/********************************************/
		
		$data['excel_report'] = array(
									array("index"=>1,"data"=>$dc_dash,"title"=>"Daily_Count")
									,array("index"=>2,"data"=>$ytd_mobile,"title"=>"Mobile YTD")
									,array("index"=>3,"data"=>$wtd_mobile,"title"=>"Mobile WTD")
									,array("index"=>4,"data"=>$daily_mobile,"title"=>"Mobile Daily")
									,array("index"=>5,"data"=>$ytd_dash,"title"=>"YTD")
									,array("index"=>6,"data"=>$wtd_dash,"title"=>"WTD")
									,array("index"=>7,"data"=>$daily_dash,"title"=>"Daily")
									,array("index"=>8,"data"=>$not_used,"title"=>"Not Used User")
									,array("index"=>9,"data"=>$moving_avg,"title"=>"7days_Moving_Average")
									,array("index"=>10,"data"=>$emails_sent,"title"=>"Email_Status")
									,array("index"=>11,"data"=>$call_status,"title"=>"Call_Status")
								);
		//$dc_dash,$ytd_mobile,$wtd_mobile,$daily_mobile,$ytd_dash,$wtd_dash,$daily_dash,$not_used,$moving_avg);
		//$this->db->close();
		
		$dt=date('d-m-Y');
		
		$filename='Prism_User_Stats_'.$dt;
		
		//$excelData=array("title"=>$filename, "file_name"=>$filename, "excel_data"=>$data,"footer"=>null,"rpt_gen_by"=>'karthi',"rpt_gen_on"=>'today');
		//print_r($data['moving_avg']); exit;
		$filename='Prism_User_Stats_'.$dt;
		$this->generateExcel($data,'Prism User Stats',null,$filename);
		exit;
	}
	
	

}
?>