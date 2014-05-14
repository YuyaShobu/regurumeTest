<?php
/**
 * top3サイト
 *
 * validate class
 * ここにポストするだけでチェックと整形をして
 * エラーメッセージの返しと整形した値の返しを行うことを目指している。
 * リターンするときは親クラスの_retをかましてください
 *
 * @copyright 2013
 * @author    yuya yamamoto
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");
require_once( 'Zend/Validate.php' );
require_once 'Zend/Validate/StringLength.php';
class shopValidate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	//データ抽出SQLクエリ
	function registValidate($param) {
		$this->validate = array();
		$this->validate['error_flg'] = false;

		//shop_name
		$this->_shopNameValidate($param);


		$this->_catchcopyValidate($param);

		$this->_bodycopyValidate($param);

		$this->_parkingValidate($param);

		$this->_accessValidate($param);

		$this->_stationValidate($param);

		$this->_approvalnumberValidate($param);

		$this->_groupnameValidate($param);

		$this->_etcValidate($param);

		//latlon
		$this->_latlonValidate($param);

		//url
		$this->_urlValidate($param);

		//address
		$this->_addressValidate($param);

		//business_day
		$this->_businessDayValidate($param);

		//business_day
		$this->_regularHoliDayValidate($param);

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
		    $this->validate['error_message'][] = '店名が入力されていません';
		    $this->validate['error_flg'] = true;
		    $this->validate['shop_name'] = "";
		} else {
			$this->validate['shop_name'] = strip_tags($param['shop_name']);
		}
	}

	protected function _catchcopyValidate ($param) {
		if (isset($param['catchcopy']) && $param['catchcopy'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 200);
			if ((!$validator_str1->isValid($param['catchcopy']))){
				$this->validate['error_message'][] = 'キャッチコピーは0-200文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['catchcopy'] = "";
			} else {
				$this->validate['catchcopy'] = strip_tags($param['catchcopy']);
			}
		}
		else {
			$this->validate['catchcopy'] = '';
		}
	}

	protected function _bodycopyValidate ($param) {
		if (isset($param['bodycopy']) && $param['bodycopy'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 200);
			if ((!$validator_str1->isValid($param['bodycopy']))){
				$this->validate['error_message'][] = 'ボディーコピーは0-200文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['bodycopy'] = "";
			} else {
				$this->validate['bodycopy'] = strip_tags($param['bodycopy']);
			}
		}
		else {
			$this->validate['bodycopy'] = '';
		}
	}

	protected function _parkingValidate ($param) {
		if (isset($param['parking']) && $param['parking'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 20);
			if ((!$validator_str1->isValid($param['parking']))){
				$this->validate['error_message'][] = '駐車場は0-20文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['parking'] = "";
			} else {
				$this->validate['parking'] = strip_tags($param['parking']);
			}
		}
		else {
			$this->validate['parking'] = '';
		}
	}

	protected function _accessValidate ($param) {
		if (isset($param['access']) && $param['access'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 200);
			if ((!$validator_str1->isValid($param['access']))){
				$this->validate['error_message'][] = 'アクセスは0-200文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['access'] = "";
			} else {
				$this->validate['access'] = strip_tags($param['access']);
			}
		}
		else {
			$this->validate['access'] = '';
		}
	}

	protected function _stationValidate ($param) {
		if (isset($param['station']) && $param['station'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 50);
			if ((!$validator_str1->isValid($param['station']))){
				$this->validate['error_message'][] = '最寄り駅は0-50文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['station'] = "";
			} else {
				$this->validate['station'] = strip_tags($param['station']);
			}
		}
		else {
			$this->validate['station'] = '';
		}
	}

	protected function _approvalnumberValidate ($param) {
		$is_error = 0;
		if (isset($param['approvalnumber1']) && $param['approvalnumber1'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 50);
			if ((!$validator_str1->isValid($param['approvalnumber1']))){
				$is_error = 1;
				$this->validate['approvalnumber1'] = "";
			} else {
				$this->validate['approvalnumber1'] = strip_tags($param['approvalnumber1']);
			}
		}
		else {
			$this->validate['approvalnumber1'] = '';
		}
		if (isset($param['approvalnumber2']) && $param['approvalnumber2'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 50);
			if ((!$validator_str1->isValid($param['approvalnumber2']))){
				$is_error = 1;
				$this->validate['approvalnumber2'] = "";
			} else {
				$this->validate['approvalnumber2'] = strip_tags($param['approvalnumber2']);
			}
		}
		else {
			$this->validate['approvalnumber2'] = '';
		}
		if (isset($param['approvalnumber3']) && $param['approvalnumber3'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 50);
			if ((!$validator_str1->isValid($param['approvalnumber3']))){
				$is_error = 1;
				$this->validate['approvalnumber3'] = "";
			} else {
				$this->validate['approvalnumber3'] = strip_tags($param['approvalnumber3']);
			}
		}
		else {
			$this->validate['approvalnumber3'] = '';
		}
		if (isset($param['approvalnumber4']) && $param['approvalnumber4'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 50);
			if ((!$validator_str1->isValid($param['approvalnumber4']))){
				$is_error = 1;
				$this->validate['approvalnumber4'] = "";
			} else {
				$this->validate['approvalnumber4'] = strip_tags($param['approvalnumber4']);
			}
		}
		else {
			$this->validate['approvalnumber4'] = '';
		}
		if (isset($param['approvalnumber5']) && $param['approvalnumber5'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 50);
			if ((!$validator_str1->isValid($param['approvalnumber5']))){
				$is_error = 1;
				$this->validate['approvalnumber5'] = "";
			} else {
				$this->validate['approvalnumber5'] = strip_tags($param['approvalnumber5']);
			}
		}
		else {
			$this->validate['approvalnumber5'] = '';
		}
		if($is_error == 1) {
			$this->validate['error_message'][] = '許認可番号は0-50文字で入力してください。';
			$this->validate['error_flg'] = true;
		}
	}

	protected function _groupnameValidate ($param) {
		if (isset($param['groupname']) && $param['groupname'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 100);
			if ((!$validator_str1->isValid($param['groupname']))){
				$this->validate['error_message'][] = '所属団体名は0-100文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['groupname'] = "";
			} else {
				$this->validate['groupname'] = strip_tags($param['groupname']);
			}
		}
		else {
			$this->validate['groupname'] = '';
		}
	}

	protected function _etcValidate ($param) {
		if (isset($param['etc']) && $param['etc'] != '') {
			$validator_str1 = new Zend_Validate_StringLength(0, 500);
			if ((!$validator_str1->isValid($param['etc']))){
				$this->validate['error_message'][] = '備考は0-500文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['etc'] = "";
			} else {
				$this->validate['etc'] = strip_tags($param['etc']);
			}
		}
		else {
			$this->validate['etc'] = '';
		}
	}

	//住所のチェック
	protected function _addressValidate ($param) {
		if (isset($param['address']) and $param['address'] =="" ) {
			$address          = $param['address'];
		    $this->validate['error_message'][] = '住所が入力されていません。住所は地図上のピンを移動して入力できます。';
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
				$this->validate['address'] = strip_tags($param['address']);
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
				$this->validate['error_message'][] = '申し訳ございませんが、もう一度地図上のピンを動かし、正確な位置を入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['latitude']  ="";
				$this->validate['longitude'] ="";
			}
		}
	}

	//URLのチェック
	protected function _urlValidate ($param) {
		//URLのバリデーション
		if (isset($param['shop_url'])) {
			$url = $param['shop_url'];
			$this->validate['shop_url']  = strip_tags($param['shop_url']);
		    $regex = "/^https?:\/\/([A-Za-z0-9;\?:@&=\+\$,\-_\.!~\*'\(\)%]+)(:\d+)?(\/?[A-Za-z0-9;\/\?:@&=\+\$,\-_\.!~\*'\(\)%#])*$/D";
		    if ($url != "") {
			    if(preg_match($regex, $url, $matches)){
			        $array = explode(".", $matches[1]);
			        if(count($array) == 1 or in_array("", $array)) {
				      	$this->validate['error_message'][] = 'URLの形が正しくありません';
					    $this->validate['error_flg'] = true;
					    $this->validate['shop_url']  ="";
			    	}
			    } else {
				    $this->validate['error_message'][] = 'URLの形が正しくありません';
					$this->validate['error_flg'] = true;
					$this->validate['shop_url']  ="";
			    }
		    }
		}
	}

			//business_day
	protected function _businessDayValidate($param) {
		if (isset($param['business_day']) and $param['business_day']) {
			$validator_str1 = new Zend_Validate_StringLength(0, 500);
			if ((!$validator_str1->isValid($param['business_day']))){
				$this->validate['error_message'][] = '営業時間は0-500文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['business_day'] = "";
			} else {
				$this->validate['business_day'] = $param['business_day'];
			}
		} else {
			$this->validate['business_day'] = "";
		}
	}

		//business_day
	protected function _regularHoliDayValidate($param) {
		if (isset($param['regular_holiday']) and $param['regular_holiday']) {
			$validator_str1 = new Zend_Validate_StringLength(0, 100);
			if ((!$validator_str1->isValid($param['regular_holiday']))){
				$this->validate['error_message'][] = '定休日は0-500文字で入力してください。';
				$this->validate['error_flg'] = true;
				$this->validate['regular_holiday'] = "";
			} else {
				$this->validate['regular_holiday'] = strip_tags($param['regular_holiday']);
			}
		} else {
			$this->validate['regular_holiday'] = "";
		}
	}
}
?>
