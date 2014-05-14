<?php
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class billValidate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	//データ抽出SQLクエリ
	function editValidate($param) {
		$this->validate = array();
		$this->validate['error_flg'] = false;

		//shop_name
		$this->_shopNameValidate($param);

		//latlon
		$this->_latlonValidate($param);

		//url
		$this->_urlValidate($param);

		//pref_select
		$this->_prefValidate($param);
		
		//city
		$this->_cityValidate($param);
		
		//address
		$this->_addressValidate($param);
		
		//staff_name
		$this->_staffnameValidate($param);
		
		//staff_email
		$this->_staffemailValidate($param);
		
		//email
		$this->_emailValidate($param);
		
		//plan_status
		$this->_planstatusValidate($param);
		
		//status
		$this->_statusValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

	//店名のチェック
	protected function _shopNameValidate ($param) {
		if (isset($param['shop_name']) and $param['shop_name'] =="") {
			$shop_name        = $param['shop_name'];
		    $this->validate['error_message']['0'] = '店名が入力されていません';
		    $this->validate['error_flg'] = true;
		    $this->validate['shop_name'] = "";
		} else {
			$this->validate['shop_name'] = $param['shop_name'];
		}
	}


	//住所のチェック
	protected function _addressValidate ($param) {
		if (isset($param['address']) and $param['address'] =="" ) {
			$address          = $param['address'];
		    $this->validate['error_message']['1'] = '住所が入力されていません。住所は地図上のピンを移動して入力できます。';
		    $this->validate['error_flg'] = true;
			$this->validate['address']   = "";
		} else {
			$address = $param['address'];
			$matches = array();
			$match_count = preg_match("/^(〒[-\d]+)?(\s+)?(.+)$/", $address, $matches);
			if($match_count > 0) {
				$address = $matches[3];
				$this->validate['address'] = $address;
			} else {
				$this->validate['address'] = $param['address'];
			}
		}
	}


	//緯度経度のチェック
	protected function _latlonValidate ($param) {
			//緯度のバリデーション
		if (isset($param['latitude']) or isset($param['longitude'])) {
			$lat = $param['latitude'];
			$lon = $param['longitude'];

			if (is_numeric($lat) and is_numeric($lon) ) {
				$this->validate['latitude']  = $param['latitude'];
				$this->validate['longitude'] = $param['longitude'];
			} else {
				$this->validate['error_message']['2'] = '申し訳ございませんが、もう一度地図上のピンを動かし、正確な位置を入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['latitude']  ="";
				$this->validate['longitude'] ="";
			}
		}
		else {
			$this->validate['error_message']['2'] = '申し訳ございませんが、もう一度地図上のピンを動かし、正確な位置を入力してください。';
			$this->validate['error_flg'] = true;
			$this->validate['latitude']  ="";
			$this->validate['longitude'] ="";			
		}
	}

	//URLのチェック
	protected function _urlValidate ($param) {
		//URLのバリデーション
		if (isset($param['shop_url'])) {
			$url = $param['shop_url'];
			$this->validate['shop_url']  = $param['shop_url'];
		    $regex = "/^https?:\/\/([A-Za-z0-9;\?:@&=\+\$,\-_\.!~\*'\(\)%]+)(:\d+)?(\/?[A-Za-z0-9;\/\?:@&=\+\$,\-_\.!~\*'\(\)%#])*$/D";
		    if ($url != "") {
			    if(preg_match($regex, $url, $matches)){
			        $array = explode(".", $matches[1]);
			        if(count($array) == 1 or in_array("", $array)) {
				      	$this->validate['error_message']['3'] = 'URLの形が正しくありません';
					    $this->validate['error_flg'] = true;
					    $this->validate['shop_url']  ="";
			    	}
			    } else {
				    $this->validate['error_message']['3'] = 'URLの形が正しくありません';
					$this->validate['error_flg'] = true;
					$this->validate['shop_url']  ="";
			    }
		    }
		    else {
		    	$this->validate['shop_url']  ="";
		    }
		}
	}
	
	//都/道/府/県
	protected function _prefValidate ($param) {
		if(empty($param['shop_id'])) {
			if (isset($param['pref_code']) and $param['pref_code'] <1) {
				$pref_code       = $param['pref_code'];
				$this->validate['error_message']['0'] = '都/道/府/県が入力されていません';
				$this->validate['error_flg'] = true;
				$this->validate['pref_code'] = 0;
			} else {
				$this->validate['pref_code'] = $param['pref_code'];
			}			
		}
		else {
			$this->validate['pref_code'] = '';
		}
	}
	
	//市/区/町/村
	protected function _cityValidate ($param) {
		if(empty($param['shop_id'])) {
			if (isset($param['city']) and $param['city'] <1) {
				$city       = $param['city'];
				$this->validate['error_message']['0'] = '市/区/町/村が入力されていません';
				$this->validate['error_flg'] = true;
				$this->validate['city'] = 0;
			} else {
				$this->validate['city'] = $param['city'];
			}
		}
		else {
			$this->validate['city'] = '';
		}		
	}

	//店長氏名
	protected function _staffnameValidate ($param) {
		if(isset($param['status']) && $param['status'] == 1) {
			if (isset($param['staff_name']) and $param['staff_name'] =="") {
				$staff_name        = $param['staff_name'];
				$this->validate['error_message']['0'] = '店長氏名が入力されていません';
				$this->validate['error_flg'] = true;
				$this->validate['staff_name'] = "";
			} else {
				$this->validate['staff_name'] = $param['staff_name'];
			}			
		}
		else {
			$this->validate['staff_name'] = $param['staff_name'];
		}
	}

	//店長メールアドレス
	protected function _staffemailValidate ($param) {
		if(isset($param['status']) && $param['status'] == 1) {
			if (isset($param['staff_email'])) {
				$staff_email = $param['staff_email'];
				$this->validate['staff_email']  = $param['staff_email'];
				$regex="/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i";
				if ($staff_email != "") {
					if(preg_match($regex, $staff_email)){
						$this->validate['staff_email'] = $param['staff_email'];
					}
					else {
						$this->validate['error_message']['3'] = '店長メールアドレスの形が正しくありません';
						$this->validate['error_flg'] = true;
						$this->validate['staff_email']  ="";
					}
				}
				else {
					$this->validate['error_message']['3'] = '店長メールアドレスが入力されていません';
					$this->validate['error_flg'] = true;
					$this->validate['staff_email']  ="";
				}
			}
			else {
				$this->validate['error_message']['3'] = '店長メールアドレスが入力されていません';
				$this->validate['error_flg'] = true;
				$this->validate['staff_email']  ="";
			}			
		}
		else {
			$this->validate['staff_email']  = $param['staff_email'];
		}
	}
	
	//請求先メールアドレス
	protected function _emailValidate ($param) {
		if (isset($param['email'])) {
			$email = $param['email'];
			$this->validate['email']  = $param['email'];
			$regex="/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i";
			if ($email != "") {
				if(preg_match($regex, $email)){
					$this->validate['email'] = $param['email'];
				}
				else {
					$this->validate['error_message']['3'] = '請求先メールアドレスの形が正しくありません';
					$this->validate['error_flg'] = true;
					$this->validate['email']  ="";
				}
			}
		}
	}
	
	//ステータス
	protected function _planstatusValidate ($param) {
		if (isset($param['plan_status']) and $param['plan_status'] =="") {
			$plan_status       = $param['plan_status'];
			$this->validate['error_message']['0'] = 'ステータスが入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['plan_status'] = "";
		} else {
			$this->validate['plan_status'] = $param['plan_status'];
		}
	}
	
	//ステータス
	protected function _statusValidate ($param) {
		if (isset($param['status']) and $param['status'] =="") {
			$status       = $param['status'];
			$this->validate['error_message']['0'] = 'ステータスが入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['status'] = "";
		} else {
			$this->validate['status'] = $param['status'];
		}
	}
}
?>
