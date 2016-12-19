<?php

class modelPrefer extends libDatabase
{

	public function __construct() {
		parent::__construct();
		$this->subscription = array('Senior Analyst' => 'AU', 'Covered Ticker' => 'CO', 'Industry' => 'IN', 'Sector' => 'IN', 'Product' => 'EL');
	}

	private function unique($str) {
		return implode(',',array_unique(explode(',',$str)));
	}

	protected function preferData() {
		//SELECT * FROM T_PM_PREFERENCE
		//SELECT * FROM T_PM_PREFERENCE_DETAILS
		//SELECT * FROM D_CONTACT_LIST_DETAIL 
		//SELECT (FirstName + '-' + LastName) AS FullName, * FROM dv_sf_contact WHERE Id = '0033000000MvmnbAAB'
		//SELECT * FROM T_CONTACT_CREDENTIAL

		//changes in query
		//p.cate_type_id = p.bm_id
		$sql = "SELECT 
			p.contact_id AS id, 
			p.contact_email AS email, 
			p.account_name AS institution, 
			p.cate_type_name AS subs_name, 
			p.bm_id AS subs_id, 
			p.status AS active, 
			(SELECT 
				(sfc.FirstName + '--' + sfc.LastName) 
				FROM dv_sf_contact AS sfc 
					WHERE sfc.Id = p.contact_id) AS name, 
			(SELECT 
				(cc.username + '--' + cc.password) 
				FROM T_CONTACT_CREDENTIAL AS cc 
					WHERE cc.contact_id = p.contact_id AND cc.status = 1) AS access
				FROM T_PM_PREFERENCE AS p 
					WHERE p.status = 1"; //CAST(p.modified_on AS DATE) = CAST(GETDATE() AS DATE) AND
		$qryRes = $this->fetch_assoc($sql);

		//changes in query
		//pd.doc_type_id = pd.bm_Typeid
		$sql1 = "SELECT 
				p.contact_id, pd.bm_Typeid as doc_id
					FROM T_PM_PREFERENCE AS p 
					INNER JOIN T_PM_PREFERENCE_DETAILS AS pd 
						ON (p.id = pd.pref_id) 
							WHERE pd.status = 1";
		$qry1Res = $this->fetch_assoc($sql1);
		$docs = array();
		foreach ($qry1Res as $key => $value) {
			if(isset($docs[$value['contact_id']])) {
				$docs[$value['contact_id']]['doc'] .= ','.$value['doc_id'];
			} else {
				$docs[$value['contact_id']] = array('contact_id' => $value['contact_id'], 'doc' => $value['doc_id']);
			}
		}
		$resArray = array();
		if(!empty($qryRes)) {
			foreach ($qryRes as $res) {
				if(isset($resArray[$res['id']])) {
					// Subscription value update
					if($res['subs_id'] != '') {
						$subs = $resArray[$res['id']][$this->subscription[$res['subs_name']]];
						if($subs == '') {
							$tempRes = explode(',',$res['subs_id']);
						} else {
							$temp = explode(',',$subs);
							$temp1 = explode(',',$res['subs_id']);
							$tempRes = array_unique(array_merge($temp, $temp1));
						}
						$resArray[$res['id']][$this->subscription[$res['subs_name']]] = implode(',', $tempRes);
					}

					// document type id update
					/*if(!$res['doc']) {
						$res['doc'] = (($docs[$res['id']]['doc'] > 0) ? $docs[$res['id']]['doc'] : 0);
					}*/
					/*if($res['doc'] != '') {
						if($resArray[$res['id']]['doc'] == '') {
							$doc = explode(',', $res['doc']);
						} else {
							$docs = explode(',',$resArray[$res['id']]['doc']);
							$cur_docs = explode(',', $res['doc']);
							$doc = array_unique(array_merge($docs, $cur_docs));
						}
						$resArray[$res['id']]['doc'] = implode(',',$doc);
					}*/
				} else {
					$res['AU'] = '';
					$res['CO'] = '';
					$res['IN'] = '';
					$res['EL'] = '';
					$res['doc'] = ((isset($docs[$res['id']]['doc']) AND $docs[$res['id']]['doc'] != '') ? $this->unique($docs[$res['id']]['doc']) : 0);
					$resArray[$res['id']] = $res;
					$resArray[$res['id']][$this->subscription[$res['subs_name']]] = $res['subs_id'];
				}
			}
		}
		return $resArray;
	}
}
?>