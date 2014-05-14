<?php
/**
 * top3サイト
 *
 * validate class
 * ここにポストするだけでチェックと整形をして
 * エラーメッセージの返しと整形した値の返しを行うことを目指している。
 * リターンするときは親クラスの_retをかましてください
 *
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class couponValidate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	//データ抽出SQLクエリ
	function changeValidate($param) {
		$this->validate = array();
		$this->validate['error_flg'] = false;
		
		//title
		$this->_titleValidate($param);

		//publication_date1
		$this->_startDateValidate($param);

		//publication_date2
		$this->_endDateValidate($param);
		
		$this->_dateValidate($param);

		//coupon
		$this->_couponValidate($param);

		//view_flg
		$this->_viewFlgValidate($param);
		
		$this->_userValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */
	
	//クーポンタイトル
	protected function _titleValidate ($param) {
		if (isset($param['title']) and $param['title'] =="") {
			$title        = $param['title'];
		    $this->validate['error_message']['0'] = 'クーポンタイトルが入力されていません';
		    $this->validate['error_flg'] = true;
		    $this->validate['title'] = "";
		} else {
			$this->validate['title'] = $param['title'];
		}
	}


	//クーポン有効期限(開始)
	protected function _startDateValidate ($param) {
		if (isset($param['publication_date1']) and $param['publication_date1'] =="" ) {
			$publication_date1          = $param['publication_date1'];
			$this->validate['error_message']['0'] = 'クーポン有効期限(開始)が入力されていません';
		    $this->validate['error_flg'] = true;
			$this->validate['publication_date1']   = "";
		} else {
			$this->validate['publication_date1'] = $param['publication_date1'];
		}
	}

	//クーポン有効期限(終了)
	protected function _endDateValidate ($param) {
		if (isset($param['publication_date2']) and $param['publication_date2'] =="" ) {
			$publication_date2          = $param['publication_date2'];
			$this->validate['error_message']['0'] = 'クーポン有効期限(終了)が入力されていません';
		    $this->validate['error_flg'] = true;
			$this->validate['publication_date2']   = "";
		} else {
			$this->validate['publication_date2'] = $param['publication_date2'];
		}
	}
	
	protected function _dateValidate ($param) {
		if(strtotime($param['publication_date1']) > strtotime($param['publication_date2'])) {
			$this->validate['error_message']['0'] = 'クーポン有効期限の形が正しくありません';
			$this->validate['error_flg'] = true;	
			$this->validate['publication_date1']   = "";
			$this->validate['publication_date2']   = "";
		}
	}
	
	//クーポン内容
	protected function _couponValidate ($param) {
		if (isset($param['coupon']) and $param['coupon'] =="" ) {
			$coupon          = $param['coupon'];
			$this->validate['error_message']['0'] = 'クーポン利用条件が入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['coupon']   = "";
		} else {
			$this->validate['coupon'] = $param['coupon'];
		}
	}
	
	//クーポン表示フラグ
	protected function _viewFlgValidate ($param) {
		if (isset($param['view_flg']) and $param['view_flg'] =="" ) {
			$view_flg          = $param['view_flg'];
			$this->validate['error_message']['0'] = 'クーポン表示フラグの形が正しくありません';
			$this->validate['error_flg'] = true;
			$this->validate['view_flg']   = "";
		} else {
			$this->validate['view_flg'] = $param['view_flg'];
		}
	}
	
	protected function _userValidate ($param) {
		if(isset($param['view_flg']) and ($param['view_flg'] == 4 or $param['view_flg'] == 5)) {
			if(isset($param['user']) && $param['user']>0) {
				$this->validate['user'] = $param['user'];
			}
			else {
				$this->validate['error_message']['0'] = 'ユーザー選んでください';
				$this->validate['error_flg'] = true;
				$this->validate['user']   = "";				
			}
		}
		else {
			$this->validate['user'] = "";
		}
	}

}
?>
